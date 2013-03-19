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

    function isString(value){return typeof value == 'string';}

    var lowercase = function(string){return isString(string) ? string.toLowerCase() : string;};

    angular.module('inposted.directives', []).
        directive('appVersion', ['version', function (version) {
            return function (scope, elm, attrs) {
                elm.text(version);
            };
        }]).
        directive('inHide', function () {
            return function (scope, element, attr) {
                scope.$watch(attr.inHide, function (value) {
                    if(toBoolean(value)){
                        element.fadeOut(parseInt(attr.inHideSpeed) || 1000);
                    }
                });
            }
        }
    );
}());