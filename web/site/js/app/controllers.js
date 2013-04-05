'use strict';

/* Controllers */

app.controller('inposted.controllers.main', function ($scope, $timeout, Interest, Post, settings, $http, PM, $dialog) {
    $scope.settings = settings;
    $scope.user = settings.user;

    $scope.verification = {
        state: 'initial',
        sendEmail: function () {
            this.state = 'pending';
            $http.get(settings.baseUrl + '/auth/sendVerificationLink').
                success(function (data, status, headers, config) {
                    $scope.verification.state = 'sent';
                }).
                error(function (data, status, headers, config) {
                    $scope.verification.state = 'error';
                    $timeout(function () {
                        $scope.verification.state = 'initial';
                    }, 3000);
                });
        }
    };

    $scope.createNewPost = function () {
        var newPost = $dialog.dialog(
            {
                controller: 'inposted.controllers.newPost',
                templateUrl: settings.baseUrl + '/post/new'
            }
        );

        newPost.open().then(function (refreshPosts) {
            if (refreshPosts) {
                loadPosts()
            }
        });
    };

    $scope.settings = settings;

    $scope.interests = Interest.query();

    $scope.owner = {
        interests: []
    };
    if (settings.page.owner) {
        if (settings.user.id == settings.page.owner.id) {
            $scope.owner.interests = $scope.interests;
        }
        else {
            $scope.owner.interests = Interest.query({userId: settings.page.owner.id});
        }
    }
    else {
        $scope.owner.interests = [];
    }

    var getFilters = function () {
        var i;
        var filters = [];

        _($scope.owner.interests).each(function (interest) {
            if (interest.checked) filters.push(interest.id)
        });


        if (!filters.length) {
            _($scope.interests).each(function (interest) {
                if (interest.checked) filters.push(interest.id)
            });
        }

        filters = _(filters).uniq();

        return filters;
    };

    $scope.sort = {
        value: 'date',
        change: function (value, $event) {
            $event.preventDefault();
            this.value = value;
            loadPosts();
        }
    };

    var pager = {
        enabled: true,
        limit: 10,
        offset: 0,

        shift: function () {
            this.offset += this.limit;
        },
        reset: function () {
            this.enable();
            this.limit = 10;
            this.offset = 0;
        },
        disable: function () {
            this.enabled = false;
        },
        enable: function () {
            this.enabled = true;
        }
    };

    var loadPosts = function () {
        pager.reset();
        pager.disable();
        if (settings.page.post) {
            $scope.posts = [new Post(settings.page.post)];
        }
        else {
            Post.query(
                {
                    sort: $scope.sort.value,
                    interests: getFilters(),
                    userId: settings.page.owner ? settings.page.owner.id : null,
                    limit: pager.limit,
                    offset: pager.offset
                },
                function (data) {
                    pager.shift();
                    if (data.length == pager.limit) {
                        pager.enable();
                    }

                    $scope.posts = data;
                }
            );
        }

    };
    loadPosts();

    $scope.loadMorePosts = function () {
        if (pager.enabled) {
            pager.disable();
            Post.query(
                {
                    sort: $scope.sort.value,
                    interests: getFilters(),
                    userId: settings.page.owner ? settings.page.owner.id : null,
                    limit: pager.limit,
                    offset: pager.offset
                },
                function (data) {
                    _(data).each(function (item) {
                        $scope.posts.push(item)
                    });
                    if (data.length == pager.limit) {
                        pager.shift();
                        pager.enable();
                    }
                }
            );
        }
    };

    $scope.vote = function (post, type) {
        if (post.author.id == settings.user.id || post.userVote) {
            return;
        }
        post.userVote = type;
        if (type != 'like') {
            post.isGood = false;
        }
        post.$vote();

        post.thanks = true;
    }


    $scope.existsInterest = false;

    $scope.suggestions = {
        main: [],
        additional: [],
        parents: [],
        promises: {},
        getActive: function () {
            for (var i in this.main) {
                if (this.main[i].active) {
                    return this.main[i];
                }
            }
            return null;
        },
        pushParent: function (interest) {
            _(this.promises).each(function(promise){$timeout.cancel(promise);});
            this.parents.push(interest);
            if (_(this.main).indexOf(interest) !== -1) {
                this.main = this.additional;
            }
            else {
                this.main = [];
            }
            this.additional = [];
            $scope.search.term = '';
        },
        popParent: function () {
            this.parents.pop();
            this.main = [];
            this.additional = [];
        },
        clear: function (parents) {
            this.main = [];
            this.additional = [];
            if (parents) {
                this.parents = [];
            }
        }
    };

    $scope.search = function () {
        $scope.suggestions.additional = [];
        if ($scope.search.term.length >= 3) {
            $scope.suggestions.promises = {};
            $scope.suggestions.main = Interest.search({term: $scope.search.term});

            Interest.exists({name: $scope.search.term}).$then(
                function (response) {
                    $scope.existsInterest = response.data == 'true';
                });
        }
        else {
            $scope.suggestions.main = [];
        }

    };

    $scope.search.term = '';

    $scope.showAdditionalSuggestions = function (parent) {
        if ($scope.suggestions.promises[parent.id]) {
            $timeout.cancel($scope.suggestions.promises[parent.id]);
        }

        _($scope.suggestions.main).each(function (i) {
            i.active = false;
        });
        parent.active = true;
        $scope.suggestions.additional = Interest.children({parentId: parent.id});
    };

    //$scope.d_showAdditionalSuggestions = _.debounce($scope.showAdditionalSuggestions, 300);

    $scope.toggleFilter = function (interest) {
        _($scope.owner.interests).each(function (i) {
            if (interest.id == i.id) i.checked = interest.checked;
        });

        _($scope.interests).each(function (i) {
            if (interest.id == i.id) i.checked = interest.checked;
        });
        loadPosts();
    };


    $scope.isFilterDisabled = function (id, group) {
        var filters = [];
        _(group).each(function (interest) {
            if (interest.checked) filters.push(interest.id)
        });
        return (filters.length > 2) && (_(filters).indexOf(id) == -1);
    };

    $scope.createInterest = function () {
        var i = new Interest;
        i.name = $scope.search.term;

        if ($scope.suggestions.parents.length) {
            i.parentId = _($scope.suggestions.parents).last().id;
        }

        i.attach = true;

        i.$save(function (i) {
            $scope.interests.push(i);
        });
        $scope.search.term = '';
        $scope.suggestions.clear();
    };

    $scope.detachInterest = function (interest) {
        Interest.detach({id: interest.id});
        $scope.interests = _($scope.interests).without(interest);
        if (interest.checked) {
            loadPosts();
        }
    };

    $scope.attachInterest = function (interest) {
        Interest.attach({id: interest.id});
//        _($scope.interests).each(function (i) {
//            i.checked = false;
//        });
        interest.checked = true;
        $scope.interests.push(interest);

        _(_($scope.interests).filter(function (item) {return item.checked == true;}).slice(0, -3)).each(function (item) {item.checked = false;});

        loadPosts();
    };

    $scope.getSearchWidth = function () {
        return 180;
    };

    $scope.hasInterest = function (interest) {
        for (var i = 0; i < $scope.interests.length; i++) {
            if ($scope.interests[i].id == interest.id) return true;
        }

        return false;
    };

    $scope.favorites = {};


    var prepareFavorites = function () {
        $scope.favorites = {};
        _(favoritePosts).each(function (post) {
            _(post.interests).each(
                function (interest) {
                    if (!(interest.id in $scope.favorites)) {
                        $scope.favorites[interest.id] = {
                            interest: interest,
                            posts: []
                        }
                    }
                    $scope.favorites[interest.id].posts.push(post);
                }
            );
        });
    };

    var favoritePosts = settings.user.isGuest ? [] : Post.favorites({}, prepareFavorites);

    $scope.toggleFavorite = function (post, add) {
        var i;

        post.isFavorite = !post.isFavorite;

        Post.toggleFavorite({id: post.id, value: post.isFavorite});

        if (add) {
            for (i = 0; i < favoritePosts.length; i++) {
                if (favoritePosts[i].id == post.id) {
                    favoritePosts[i].isFavorite = post.isFavorite;
                    return;
                }
            }
            favoritePosts.push(post);
            prepareFavorites();
        }
        else {
            for (i = 0; i < $scope.posts.length; i++) {
                if ($scope.posts[i].id == post.id) {
                    $scope.posts[i].isFavorite = post.isFavorite;
                }
            }
        }


    }


    //==============================PM==============================
    if (!settings.user.isGuest) {
        $scope.pm = new PM(
            {
                topic: '',
                body: ''
            }
        );

        var loadPms = function () {
            if (settings.page.loadPms) {
                PM.query(function (data) {
                    $scope.pms = data;
                    $scope.unreadPmsCount = 0;
                    $timeout(loadPms, 30000);
                });
            }
            else {
                $http.get(settings.baseUrl + '/pm/unreadCount').then(function (response) {
                    $scope.unreadPmsCount = response.data;
                    $timeout(loadPms, 30000);
                })
            }
        };

        loadPms();


        $scope.showPM = function (to, topic) {
            $scope.pm.to = to;
            if (topic) {
                $scope.pm.topic = topic;
            }
            //not angular way but whatever
            $('#modalMessage').modal('show');
        };

        $scope.sendPM = function () {
            var to = $scope.pm.to;
            $scope.pm.$send(function (sent) {
                sent.to = to;
                if (_(sent.errors).isEmpty()) {
                    $scope.pm = new PM(
                        {
                            topic: '',
                            body: ''
                        }
                    );

                    //not angular way but whatever
                    $('#modalMessage').modal('hide');
                }
            }, function (response) {
                $scope.pm.error = response.data.message;
            });
        }
    }


    //=====Hints======
    if (settings.user.showHint) {
        var hints = $dialog.dialog(
            {
                controller: 'inposted.controllers.hints',
                templateUrl: settings.baseUrl + '/hint/template'
            }
        );

        hints.open();
    }

});

app.controller('inposted.controllers.hints', function ($scope, dialog, User, Hint, settings) {
    Hint.query(function (hints) {
        var i;
        var index = 0;
        if (!hints.length) {
            dialog.close();
        }

        if (settings.user.lastHint) {
            for (i = 0; i < hints.length; i++) {
                if (hints[i].id == settings.user.lastHint) {
                    index = i + 1;
                    break;
                }
            }
        }

        var showHint = function (shift) {
            if ('next' == shift) {
                index += 1;
            }
            else if ('previous' == shift) {
                index += -1;
            }

            if (index < 0) {
                index = hints.length - 1;
            }
            else if (index >= hints.length) {
                index = 0;
            }

            $scope.hint = hints[index];
            User.save({lastHint: $scope.hint.id});
        };

        showHint();

        $scope.next = function () {
            showHint('next');
        };

        $scope.previous = function () {
            showHint('previous');
        };

        $scope.close = function (disable) {
            if (disable) {
                User.save({enabledHints: false});
            }
            dialog.close();
        }
    });
});


app.controller('inposted.controllers.newPost', function ($scope, Interest, Post, dialog, $timeout) {
    $scope.newPost = new Post();

    $scope.interests = Interest.query();

    $scope.createNewPost = function () {
        var interests = [];
        _($scope.interests).each(function (interest) {
            if (interest.checked) interests.push(interest.id)
        });

        var post = $scope.newPost;
        if (!(post.content && post.content.length)) {
            post.error = 'Write something';
        }
        else if (interests.length) {
            post.inInterests = interests;
            post.$save(
                function (saved) {
                    if (saved.success) {
                        dialog.close(!saved.isModerated);
                        $scope.newPost = new Post();
                    }
                }
            );
        }
        else {
            post.error = 'Select some interests';
        }
    };

    $scope.createInterest = function () {
        var i = new Interest;
        i.name = $scope.search.term;

        if ($scope.suggestions.parents.length) {
            i.parentId = _($scope.suggestions.parents).last().id;
        }

        i.attach = false;

        i.$save(function (i) {
            i.checked = true;
            $scope.interests.push(i);
        });
        $scope.search.term = '';
        $scope.suggestions.clear();
    };

    $scope.detachInterest = function (interest) {
        $scope.interests = _($scope.interests).without(interest);
    };

    $scope.attachInterest = function (interest) {
        interest.checked = true;
        $scope.interests.push(interest);
    };

    $scope.hasInterest = function (interest) {
        for (var i = 0; i < $scope.interests.length; i++) {
            if ($scope.interests[i].id == interest.id) return true;
        }

        return false;
    };

    $scope.close = function () {
        dialog.close();
    };

    $scope.isFilterDisabled = function (id, group) {
        return false;
    };


    //====Copypaste of suggestions logic. Need to be rethinked

    $scope.existsInterest = false;

    $scope.suggestions = {
        main: [],
        additional: [],
        parents: [],
        promises: {},
        getActive: function () {
            for (var i in this.main) {
                if (this.main[i].active) {
                    return this.main[i];
                }
            }
            return null;
        },
        pushParent: function (interest) {
            _(this.promises).each(function(promise){$timeout.cancel(promise);});

            this.parents.push(interest);
            if (_(this.main).indexOf(interest) !== -1) {
                this.main = this.additional;
            }
            else {
                this.main = [];
            }
            this.additional = [];
            $scope.search.term = '';
        },
        popParent: function () {
            this.parents.pop();
            this.main = [];
            this.additional = [];
        },
        clear: function (parents) {
            this.main = [];
            this.additional = [];
            if (parents) {
                this.parents = [];
            }
        }
    };

    $scope.search = function () {
        $scope.suggestions.additional = [];
        if ($scope.search.term.length >= 3) {
            $scope.suggestions.promises = {};
            $scope.suggestions.main = Interest.search({term: $scope.search.term});

            Interest.exists({name: $scope.search.term}).$then(
                function (response) {
                    $scope.existsInterest = response.data == 'true';
                });
        }
        else {
            $scope.suggestions.main = [];
        }

    };

    $scope.search.term = '';
    $scope.showAdditionalSuggestions = function (parent) {
        if ($scope.suggestions.promises[parent.id]) {
            $timeout.cancel($scope.suggestions.promises[parent.id]);
        }

        _($scope.suggestions.main).each(function (i) {
            i.active = false;
        });
        parent.active = true;
        $scope.suggestions.additional = Interest.children({parentId: parent.id});
    };

});