app.controller('BrowseCtrl', ['$scope', '$stateParams', function($scope, $stateParams) {
	$scope.ctrl = $stateParams.ctrl;
	$scope.folds = [
	    {name: 'Semua', filter: ''},
	    {name: 'Pencahayaan', filter: 'pencahayaan'},
	    {name: 'Saklar', filter: 'saklar'},
	    {name: 'Kelistrikan', filter: 'kelistrikan'}
	];
}]);

app.controller('BrowseListCtrl', ['$scope', 'BrowseService', '$stateParams', function($scope, BrowseService, $stateParams) {
	$scope.fold = $stateParams.fold;
	$scope.ctrl = $stateParams.ctrl;
	BrowseService.browseAll($scope.ctrl)
		.then(function(data) {
	    	$scope.items = data;
		});
}]);

app.controller('BrowseDetailCtrl', ['$scope', 'BrowseService', '$stateParams', function($scope, BrowseService, $stateParams) {
	BrowseService.getById($stateParams.ctrl, $stateParams.itemId)
		.then(function(data) {
		    $scope.item = data;
		 })
}]);