'use strict';

/* Filters */

angular.module('inposted.filters', []).
    filter('cut', [function () {
        return function (string) {
            if(string.length > 250){
                var result = string.replace(/^(.{0,250})\s.*/, '$1');

                //TODO: prevent html link break

                if (result.length < string.length) {
                    result += '...';
                }

                return result;
            }

            return string;
        }
    }]);
