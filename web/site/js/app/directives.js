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
        directive('inDisabled', function (version) {
            return function (scope, element, attrs) {
                element.click(function (e) {
                    e.preventDefault();
                    return false;
                });
            };
        });
















    //ajax widget


    var Inposted ={
        ajaxError: function (info) {
            alert(info.responseText)
        },

        showAjaxLoader: function () {
            var loader = $('<div>')
                .attr('id', 'ajax-loader')
                .css({
                    background: 'url("/img/ajax-loader-big.gif") no-repeat scroll center center gray',
                    position: 'absolute',
                    top: 0,
                    left: 0,
                    opacity: 0.5,
                    'z-index': 1000,
                    height: '100%',
                    width: '100%'
                })
            $('body').append(loader);
        },
        hideAjaxLoader: function () {
            $('#ajax-loader').remove();
        }
    };

    $(document).on('click', '.ajax-widget a.ajax', function (e) {
        e.preventDefault();
        var widget = $(this).closest('.ajax-widget');

        var showLoader = !$(this).data('no-loader');

        showLoader && Inposted.showAjaxLoader();
        $.ajax(
            $(this).attr('href'),
            {
                success: function (data) {
                    widget.replaceWith(data);
                    showLoader && Inposted.hideAjaxLoader();
                },
                error: Inposted.ajaxError
            }
        )
    });

    $(document).on('submit', '.ajax-widget form.ajax', function (e) {
        e.preventDefault();
        var widget = $(this).closest('.ajax-widget');
        Inposted.showAjaxLoader();
        $.ajax(
            $(this).attr('action'),
            {
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function (data) {
                    widget.replaceWith(data);
                    Inposted.hideAjaxLoader();
                },
                error: Inposted.ajaxError
            }
        );
    });




}());