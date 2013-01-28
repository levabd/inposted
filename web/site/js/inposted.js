var Inposted = {
    refreshOwnInterests: function (verb) {
        var own_interests = $('#own-interests');
        $.get(
            own_interests.data('url'),
            {verb: verb},
            function (data) {
                own_interests.replaceWith(data);
            }
        )
    }
}

jQuery(function ($) {
    //right bar own interests search
    $(document).on('keyup', '#quicksearch', function (e) {
        var self = this;
        var value = $(self).val();
        if (value.length > 2) {
            $.ajax(
                $(self).data('url'),
                {
                    data: {verb: value},
                    success: function (data) {
                        $('#side_search_results').html(data)
                    }
                }
            )
        }
        else {
            $('#side_search_results').html('')
        }
    });

    var createNewInterest = function () {
        var input = $("#create-new-interest-input");
        var value = input.val();
        if (value.length > 2) {
            $.ajax(
                input.data('url'),
                {
                    data: {name: value},
                    success: function (data) {
                        if (true === data) {
                            Inposted.refreshOwnInterests();
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

    $(document).on('keyup', "#create-new-interest-input", function (e) {
        if (13 == e.which) {
            createNewInterest();
        }
    });

    $(document).on('click', "#create-new-interest-button", createNewInterest);

    $(document).on('click', ".attach-interest", function () {
        var verb = $('#create-new-interest-input').data('verb');
        $.ajax(
            $(this).data('url'),
            {
                success: function (data) {
                    if (true === data) Inposted.refreshOwnInterests(verb)
                },
                error: function (info) {
                    alert(info.responseText)
                },
                dataType: 'json'
            }
        );
    });


});