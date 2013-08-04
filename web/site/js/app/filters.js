'use strict';

/* Filters */

angular.module('inposted.filters', []).
    filter('cut', [function () {
        return function (string) {
            var result = string.replace(/^(.{0,250})\s.*/, '$1');
            if (result.length < string.length) {
                result += '...';
            }

            return result;
        }
    }]);
