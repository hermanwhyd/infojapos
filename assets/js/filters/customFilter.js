'use strict';

/* Filters */
angular.module('app')

    /**
     * required load hashids.min.js
     */
    .filter('encode', function() {
        return function(data) {
            hashids = new Hashids("");
            return hashids(data);
        }
    })

    /**
     * need load the moment.js to use this filter. 
     */ 
    .filter('fromNow', function() {
        return function(date) {
            return moment(date).fromNow();
        }
    });