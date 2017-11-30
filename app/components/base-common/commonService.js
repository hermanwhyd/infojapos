'use strict';
 
app.factory('CommonService', ['$http', '$rootScope', '$log', '$q', function ($http, $rootScope, $log, $q) {

	return {

        fetchPilihan: function(grupList) {
			var urlPath = [$rootScope.config.apiUrl, 'pilihan', grupList.join(';')].join('/');
            return $http({method: 'get', url: urlPath, headers:{'Content-Type': 'application/json'}})
                .then(
                    function(response) {
                        if (typeof response.data === 'object') {
							return response.data;
						} else {
							return $q.reject(response.data);
						}
                    },
                    function(errResponse) {
                        $log.error('Error while fetching on controller /pilihan return statuscode ' + errResponse.status);
                        return $q.reject(errResponse);
                    }
                );
        },
        
		fetchAll: function() {
			var urlPath = [$rootScope.config.apiUrl, $rootScope.$stateParams.ctrl].join('/');
            return $http({method: 'get', url: urlPath, headers:{'Content-Type': 'application/json'}})
                .then(
                    function(response) {
                        if (typeof response.data === 'object') {
							return response.data;
						} else {
							return $q.reject(response.data);
						}
                    },
                    function(errResponse) {
                        $log.error('Error while fetching on controller /' + $rootScope.$stateParams.ctrl + ' return statuscode ' + errResponse.status);
                        return $q.reject(errResponse);
                    }
                );
		},

		fetchOne: function(id) {
			var urlPath = [$rootScope.config.apiUrl, $rootScope.$stateParams.ctrl, id].join('/');
            return $http({method: 'get', url: urlPath, headers:{'Content-Type': 'application/json'}})
                .then(
                    function(response) {
                        if (typeof response.data === 'object') {
							return response.data;
						} else {
							return $q.reject(response.data);
						}
                    },
                    function(errResponse) {
						$log.error('Error while fetching on controller /' + $rootScope.$stateParams.ctrl + '/' + id + ' return statuscode ' + errResponse.status);
                        return $q.reject(errResponse);
                    }
                );
		},
		
    	saveOrUpdate: function(ctrl, func, model) {
    		var urlPath = 'api-platform/' + ctrl + '.php?' + 'act=' + func;
			return $http({method: 'post', url: urlPath, params:model, headers:{'Content-Type': 'application/json'}})
			.then(function success(response) {
				if (typeof response.data === 'object') {
                    return response.data;
                } else {
                    return $q.reject(response.data);
                }
			}, function error(response) {
				return $q.reject(response.data);
			});
		},
		
		delete: function(id) {
			var urlPath = [$rootScope.config.apiUrl, $rootScope.$stateParams.ctrl, id].join('/');
            return $http({method: 'delete', url: urlPath, headers:{'Content-Type': 'application/json'}})
                .then(
                    function(response) {
                        if (typeof response.data === 'object') {
							return response.data;
						} else {
							return $q.reject(response.data);
						}
                    },
                    function(errResponse) {
                        $log.error('Error while fetching on controller /' + $rootScope.$stateParams.ctrl + ' return statuscode ' + errResponse.status);
                        return $q.reject(errResponse);
                    }
                );
		}

    }
}]);