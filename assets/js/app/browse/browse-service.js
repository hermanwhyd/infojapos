app.factory('BrowseService', ['$http', "$q", function ($http, $q) {
	return {
		browseAll: function(ctrl) {
			var urlPath = [ctrl, 'list-paged.json'].join('/');
			return $http({method: 'post', url: urlPath, headers:{'Content-Type': 'application/json'}})
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
    	getById: function(ctrl, id) {
    		var urlPath = [ctrl, 'get-data', id].join('/');
			return $http({method: 'get', url: urlPath, headers:{'Content-Type': 'application/json'}})
			.then(function success(response) {
				if (typeof response.data === 'object') {
                    return response.data;
                } else {
                    return $q.reject(response.data);
                }
			}, function error(response) {
				return $q.reject(response.data);
			});
    	}
    }
}]);