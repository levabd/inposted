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
        directive('inSearch', function () {
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
        })
}());