'use strict';

/* Directives */

(function () {
    function toBoolean(value) {
        if (value && value.length !== 0) {
            var v = lowercase("" + value);
            value = !(v == 'f' || v == '0' || v == 'false' || v == 'no' || v == 'n' || v == '[]');
        } else {
            value = false;
        }
        return value;
    }

    var lowercase = function (string) {
        return angular.isString(string) ? string.toLowerCase() : string;
    };

    angular.module('inposted.directives', []).
        directive('appVersion', ['version', function (version) {
            return function (scope, elm, attrs) {
                elm.text(version);
            };
        }]).
        directive('inHide',function () {
            return function (scope, element, attr) {
                scope.$watch(attr.inHide, function (value) {
                    if (toBoolean(value)) {
                        element.fadeOut(parseInt(attr.inHideSpeed) || 1000);
                    }
                });
            }
        }
    ).
        directive('inSuggest', ['$timeout', function ($timeout) {
            return function (scope, element, attr) {
                var promises = scope.$parent.suggestions.promises;
                var id = scope.interest.id;
                element.
                    mouseenter(function () {
                        promises[id] = $timeout(function () {
                            promises[id] = null;
                            scope.$parent.showAdditionalSuggestions(scope.interest);
                        }, 1500);
                    }).
                    mouseleave(function () {
                        $timeout.cancel(promises[id]);
                        promises[id] = null;
                    })
            }
        }]).
        directive('inSearch',function () {
            return function (scope, element, attr) {
                element.
                    click(function () {
                        $(this).find('input').focus();
                    }).
                    find('input').
                    focus(function () {
                        element.addClass('with-focus')
                    }).
                    blur(function () {
                        element.removeClass('with-focus')
                    })
            }
        }).
        directive('inDisabled',function (version) {
            return function (scope, element, attrs) {
                element.click(function (e) {
                    e.preventDefault();
                    return false;
                });
            };
        }).
        directive('inInfinitePosts',function () {
            return function (scope, element, attrs) {
                $(document).on('scroll', _.debounce(function () {
                    if ($('.post:below-the-fold').length < 2) {
                        scope.$apply(attrs.inInfinitePosts)
                    }
                }, 100))
            }
        }).
        directive('inNewPost',function ($parse) {
            return function (scope, element, attrs) {
                element.on('hide', function () {
                    if (!scope.$$phase) {
                        scope.$apply(
                            function () {
                                $parse(attrs.inNewPost).assign(scope, false);
                            }
                        );
                    }
                });
                scope.$watch(attrs.inNewPost, function (value) {
                    element.modal(value ? 'show' : 'hide');
                });
            }
        }).
        directive('inDots', ['$timeout', function ($timeout) {
            return function (scope, element, attrs) {
                var text = element.text().trim();
                var dots = '';

                var addDot = function () {
                    if ('...' == dots) {
                        dots = '';
                    }
                    else {
                        dots += '.';
                    }

                    if (scope.$eval(attrs.inDots)) {
                        $timeout(function () {
                            addDot();
                        }, 300);
                    }
                    else {
                        dots = '';
                    }

                    element.text(text + dots);

                };

                scope.$watch(attrs.inDots, function (value) {
                    if (value) {
                        addDot();
                    }
                });


            }
        }]).
        directive('inKeyUp',function () {
            return function (scope, element, attributes) {
                var mod = attributes.inKeyUpMod;
                var key = attributes.inKeyUpKey;

                element.bind('keyup', function (event) {
                    if ((!key || key == event.which) && (!mod || event[mod + 'Key'])) {
                        scope.$apply(attributes.inKeyUp);
                    }
                });
            }
        }).
        directive('inBlur',function () {
            return function (scope, elem, attrs) {
                elem.bind('blur', function () {
                    scope.$apply(attrs.inBlur);
                });
            };
        }).
        directive('inFileUpload',function ($q) {
            return function (scope, element, attributes) {
                var deferred;

                element.fileupload({
//                    dataType: 'json',
                    add: function (e, data) {
                        scope[attributes.inFileUpload] = function () {
                            deferred = $q.defer();
                            data.submit();
                            return deferred.promise;
                        }
                    },
                    done: function (e, data) {
                        scope[attributes.inFileUpload] = null;
                        deferred.resolve(data);
                    },
                    fail: function (e, data) {
                        scope[attributes.inFileUpload] = null;
                        deferred.reject(data);
                    }
                });
            }
        }).
        directive('autoFillSync', function ($timeout) {
            return {
                require: 'ngModel',
                link: function (scope, elem, attrs, ngModel) {
                    var origVal = elem.val();
                    var sync = function () {
                        var newVal = elem.val();
                        if (ngModel.$pristine && origVal !== newVal) {
                            ngModel.$setViewValue(newVal);
                        }
                    };

                    //this should ensure that login WILL work :)
                    if (attrs.autoFillSync) {
                        $(attrs.autoFillSync).bind('click', sync);
                    }
                    $timeout(sync, 500);
                }
            }
        });
}());