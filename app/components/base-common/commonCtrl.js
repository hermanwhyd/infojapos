'use strict';

/** List Table Controller **/
app.controller('CommonListCtrl', ['$scope', '$http', '$filter', '$stateParams', '$log', '$modal', 'toaster', 'CommonService', function($scope, $http, $filter, $stateParams, $log, $modal, toaster, CommonService) {
	var self = this;
	
	// paging
	self.pageSizeList = [2, 10, 25, 50, 100];
	self.page = 1;
	self.pageSize = self.pageSizeList[0];
	self.total = 0;
	self.pagedModels = {indexLower: 0, indexUpper: 0, currentModels:[]};
	
	self.modelCmd = {ctrl: $stateParams.ctrl, func: $stateParams.func};
	self.searchCriteria = '';
	self.models = [];
	self.lPromise;
	self.temp = {lastPage: -1};
	self.msgConfirmDelete = {ctrl: 'Jamaah', label: 'Herman W'};

	self.pagingModels = function(page, pageSize, total) {
		// don't remove, its needed to make consist of current page
		self.page = page;
		self.total = total;
		self.pageSize = pageSize;

		// logger
		$log.info({page: page,pageSize: pageSize,total: total});
		
		// do search
		self.search();
	};

	self.search = function() {
		self.pagedModels.currentModels = $filter('filter')(self.models, self.searchCriteria);
		self.total = (self.pagedModels.currentModels) ? self.pagedModels.currentModels.length : 0;
		self.pagedModels.indexLower = 1 + (self.pageSize * (self.page - 1));
		self.pagedModels.indexUpper = (self.pageSize * self.page) > self.total ? self.total : (self.pageSize * self.page);
	}

	// watch changes input search
	$scope.$watch(function() {
		return self.searchCriteria
	}.bind(self), function(newValue, oldValue, scope) {
		if (oldValue === '') {self.temp.lastPage = self.page; self.page = 1;}
		if (newValue === '' && self.temp.lastPage != -1) self.page = self.temp.lastPage;
		self.search();
	}.bind(self));

    self.fetchAll = function() {
		self.lPromise = CommonService.fetchAll()
            .then(
                function(d) {
					self.models = d;
					self.search();
                },
                function(errResponse) {
					$log.error('Error while fetching Currencies. Error ' + errResponse);
					toaster.pop('error', 'Error Caught', 'Error while fetching Currencies');
                }
            );
	};
	
	self.remove = function(item) {
		console.log('id to be deleted', item.id);
		var idx = self.pagedModels.currentModels.indexOf(item);
		self.pagedModels.currentModels.splice(idx, 1);
		self.delete(item);
	};
	
    self.delete = function(item) {
        self.lPromise = CommonService.delete(item.id)
            .then(
                function(d) {
					// delete from origin
					var idx = self.models.indexOf(item);
					self.models.splice(idx, 1);
				},
				function(errResponse) {
					$log.error('Error while deleting Model. Error: ' + errResponse);
					toaster.pop('error', 'Error Caught', 'Error while deleting Model');

					// roll back, back to origin
					self.search();
                }
            );
	};
	
	self.edit = function(id) {
        console.log('id to be edited', id);
        for (var i = 0; i < self.models.length; i++) {
            if (self.models[i].id == id) {
                self.model = angular.copy(self.jamaahList[i]);
                break;
            }
        }
	};
	
	self.openConfirmDeleteModal = function (item) {
		var modalInstance = $modal.open({
		  templateUrl: 'confirmDeleteModalContent.html',
		  controller: 'ModalInstanceCtrl',
		  size: 'xs', // sm, lg
		  resolve: {
			msg: function () {
			  return self.msgConfirmDelete;
			}
		  }
		});
  
		modalInstance.result.then(function () {
		  self.remove(item);
		}, function () {
		  $log.info('Modal dismissed at: ' + new Date());
		});
	};

	// init function
	self.fetchAll();	
	window.scope = self;
}]);


/** Form Controller **/
app.controller('CommonFormCtrlV1', ['$scope', '$http', '$stateParams', 'toaster', 'CommonService', function($scope, $http, $stateParams, toaster, CommonService) {
	var init;

	return $scope.list, $scope.modelCmd, $scope.fetchData = function() {
		CommonService.findModelById($scope.modelCmd.ctrl, $scope.modelCmd.func, $scope.form.model.id)
			.then(function success(data) {
				$scope.form.model = angular.copy(data);
				return $scope.form
			}, function error(data) {
				toaster.pop('error', 'Error Caught', angular.isDefined(data.message) ? data.message : data.developerMessage);
			});
	}, $scope.submit = function() {
			var act = ($scope.form.model.id == '') ? 'Add' : 'Update';
			CommonService.saveOrUpdate($scope.modelCmd.ctrl, act, $scope.form.model)
			.then(function success(data) {
				toaster.pop('success', "Data berhasil disimpan!");
			}, function error(data) {
				toaster.pop('error', 'Error Caught', angular.isDefined(data.message) ? data.message : data.developerMessage);
			});
	}, (init = function() {
		// init variable
		$scope.form = {model: {id: $stateParams.id}};
		$scope.modelCmd = {ctrl: $stateParams.ctrl, func: $stateParams.func}; 
		// fetch data
		$scope.fetchData();
    })();
}]);

/** Profile Controller **/
app.controller('CommonProfileCtrl', ['$scope', '$http', '$stateParams', '$filter', 'toaster', 'CommonService', function($scope, $http, $stateParams, $filter, toaster, CommonService) {
	var init;
	
	return $scope.form, $scope.getModel = function() {
		CommonService.findModelById($filter('lowercase')($stateParams.tipe), $stateParams.id)
			.then(function success(data) {
				$scope.form.model = angular.copy(data);
				return $scope.form
			}, function error(data) {
				toaster.pop('error', 'Error Caught', angular.isDefined(data.message) ? data.message : data.developerMessage);
			});
	}, $scope.togleEdit = function() {
		$scope.form.editing = !$scope.form.editing;
	}, (init = function() {
		// init variable
		$scope.form = {model: {}, editing: false};
		
		// get data
		$scope.getModel();
    })();
}]);

/**
 * Default Controller for Modal
 */
app.controller('ModalInstanceCtrl', ['$scope', '$modalInstance', 'msg', function($scope, $modalInstance, msg) {
    $scope.msg = msg;
    $scope.ok = function () {
      $modalInstance.close();
    };

    $scope.cancel = function () {
      $modalInstance.dismiss('cancel');
    };
}]); 