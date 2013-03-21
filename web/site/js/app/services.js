'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('inposted.services', ['ngResource']).
    value('version', '0.1').
    factory('Interest', function ($resource, settings) {
        return $resource(settings.baseUrl + '/interest/:action/', {}, {
            save: {method: 'POST', params: {action: 'create'}},
//            query: {method: 'POST', params: {}, isArray: true},

            search: {method: 'GET', params: {action: 'search'}, isArray: true},
            children: {method: 'GET', params: {action: 'children'}, isArray: true},

            attach: {method: 'GET', params: {action: 'attach'}},
            detach: {method: 'GET', params: {action: 'attach', detach: true}},
            exists: {method: 'GET', params: {action: 'exists'}}
        });
    }).
    factory('Post', function ($resource, settings) {
        return $resource(settings.baseUrl + '/post/:action/', {}, {
            query: {method: 'POST', params: {}, isArray: true},
            save: {method: 'POST', params: {action: 'create'}},
            vote: {method: 'POST', params: {action: 'vote'}},
            favorites: {method: 'GET', params: {action: 'favorites'}, isArray: true},
            toggleFavorite: {method: 'GET', params:{action: 'toggleFavorite'}}
//            query: {method: 'GET', params: {}, isArray: true}

        });
    })
;
