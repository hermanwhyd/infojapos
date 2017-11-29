'use strict';

/* Controllers */
  // signin controller
app.controller('SigninFormController', ['$scope', '$http', '$state', function($scope, $http, $state) {
    $scope.user = {};
    $scope.authError = null;
    $scope.login = function() {
		$scope.authError = null;
		// Try to login
		$http.post('login', {email: $scope.user.email, password: $scope.user.password})
		.then(function(response) {
			if (response.data) {
				$scope.app.session = {user: response.data, isLogged: true};
				console.debug($scope.app.session);
				$state.go('app.dashboard-v1');
			} else {
				$scope.authError = 'Email or password not registered!';
			}
		}, function(ex) {
			$scope.authError = 'Server Error';
		});
	};
}]);