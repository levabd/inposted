const KEY_ENTER = 13;
const KEY_SHIFT = 16;
var Inposted = {
    MAX_POST_SIZE: null,
    refreshOwnInterests: function (verb, widgetId) {
        var own_interests = $('.own-interests');
        $.get(
            own_interests.data('url'),
            {verb: verb, widgetId: widgetId},
            function (data) {
                own_interests.replaceWith(data);
            }
        )
    },
    updateInterestCheckboxesState: function (group) {
        var selector = '.own-interest[data-group=' + group + ']';
        var disableMore = $(selector + ':checked').length > 2;
        $(selector).not(':checked').prop('disabled', disableMore);
    }

}

jQuery(function ($) {
    //right bar own interests search
    $(document).on('keyup', '.quicksearch', function (e) {
        var self = $(this);
        var value = self.val();
        $('.quicksearch').val(value);
        if (value.length > 2) {
            $.ajax(
                self.data('url'),
                {
                    data: {verb: value, except: self.data('except')},
                    success: function (data) {
                        $('.side_search_results').html(data)
                    }
                }
            )
        }
        else {
            $('.side_search_results').html('')
        }
    });

    var createNewInterest = function (input) {
        input = $(input);
        var widgetId = input.closest('.own-interests').attr('id');
        var value = input.val();
        if (value.length > 2) {
            $.ajax(
                input.data('url'),
                {
                    data: {name: value},
                    success: function (data) {
                        if (true === data) {
                            Inposted.refreshOwnInterests(null, widgetId);
                        }
                        else {
                            alert(data);
                        }
                    },
                    dataType: 'json'
                }
            )
        }
    };

    $(document).on('keyup', ".create-new-interest-input", function (e) {
        e.preventDefault();
        if (KEY_ENTER == e.which) {
            createNewInterest(this);
        }
    });

    $(document).on('click', ".create-new-interest-button", function (e) {
        e.preventDefault();
        createNewInterest($(this).data('input-id'));
    });

    $(document).on('click', ".attach-interest", function (e) {
        e.preventDefault();
        var widgetId = $(this).closest('.own-interests').attr('id');

        var verb = $('.create-new-interest-input').data('verb');
        $.ajax(
            $(this).data('url'),
            {
                success: function (data) {
                    if (true === data) Inposted.refreshOwnInterests(verb, widgetId)
                },
                error: function (info) {
                    alert(info.responseText)
                },
                dataType: 'json'
            }
        );
    });

    //do not enter new line if shift key is held
    $(document).on('keydown', "#create-post-textarea", function (e) {
        if (KEY_ENTER == e.which && e.shiftKey) {
            e.preventDefault();
        }
    });
    $(document).on('keyup paste', "#create-post-textarea", function (e) {
        var handler = function () {
            var counter = $('#create-post-left');
            var value = Inposted.MAX_POST_SIZE - $('#create-post-textarea').val().length;
            counter.text(value);
            if (value < 0) {
                $(counter.parents()[0]).addClass('text-error');
            }
            else {
                $(counter.parents()[0]).removeClass('text-error');
            }
        };

        if ('paste' == e.type) {
            setTimeout(handler, 0);
        }
        else {
            if (KEY_ENTER == e.which && e.shiftKey) {
                //creating new post
                var form = $(this).closest('form');
                var interests = $('.own-interest[data-group=new-post-interests]:checked');
                if (interests.length && $(this).val().trim().length) {
                    interests.each(
                        function (index, element) {
                            var input = $('<input>').attr(
                                {
                                    type: 'hidden',
                                    name: 'Post[inInterests][]',
                                    value: $(element).val()
                                }
                            );
                            form.append(input);
                        }
                    );

                    form.submit();
                }
            }
            else {
                handler();
            }
        }
    });

    $(document).on('change', '.own-interest', function () {
        Inposted.updateInterestCheckboxesState($(this).data('group'));
    })


});