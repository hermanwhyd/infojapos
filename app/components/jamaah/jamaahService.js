'use strict';

app.factory('JamaahService', ['$http', '$rootScope', '$q', function($http, $rootScope, $q) {

    return {

        createJamaah: function(jamaah) {
            return $http.post('http://localhost:8080/Spring4MVCAngularJSExample/jamaah/', jamaah)
                .then(
                    function(response) {
                        return response.data;
                    },
                    function(errResponse) {
                        console.error('Error while creating jamaah');
                        return $q.reject(errResponse);
                    }
                );
        },

        updateJamaah: function(jamaah, id) {
            return $http.put('http://localhost:8080/Spring4MVCAngularJSExample/jamaah/' + id, jamaah)
                .then(
                    function(response) {
                        return response.data;
                    },
                    function(errResponse) {
                        console.error('Error while updating jamaah');
                        return $q.reject(errResponse);
                    }
                );
        },

        deleteJamaah: function(id) {
            return $http.delete('http://localhost:8080/Spring4MVCAngularJSExample/jamaah/' + id)
                .then(
                    function(response) {
                        return response.data;
                    },
                    function(errResponse) {
                        console.error('Error while deleting jamaah');
                        return $q.reject(errResponse);
                    }
                );
        }

    };

}]);