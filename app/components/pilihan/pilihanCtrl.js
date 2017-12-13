'use strict';

app.controller('PilihanController', ['$scope', '$http', '$filter', '$stateParams', '$log', '$modal', 'toaster', 'CommonService', function($scope, $http, $filter, $stateParams, $log, $modal, toaster, CommonService) {
	var self = this;
	
	//self.models = [];
    self.lPromise;
    self.list = {models: [], model: {options:[]}};

	self.fetchAll = function() {
		self.lPromise = CommonService.fetchAll()
            .then(
                function(d) {
                    self.list.models = d;
                    self.list.model = self.list.models[0];
                    self.list.models[0].selected = true;
                },
                function(errResponse) {
					$log.error('Error while fetching Currencies. Error ' + errResponse);
					toaster.pop('error', 'Error Caught', 'Error while fetching Currencies');
                }
            );
	};
    
    // remove item from models
	self.remove = function(order, item) {
        self.delete(order, item);
	};
    
    // action delete to api
    self.delete = function(order, item) {
        if (order === 'option') {
            self.lPromise = CommonService.delete(item.id)
            .then(
                function(d) {
					// delete from origin
                    var idx = self.list.model.options.indexOf(item);
                    self.list.model.options.splice(idx, 1);
				},
				function(errResponse) {
					$log.error('Error while deleting Model. Error: ' + errResponse);
					toaster.pop('error', 'Error Caught', 'Error while deleting Model by Option');
                }
            );
        } else if (order === 'grup') {
            self.lPromise = CommonService.delete(item.grup)
            .then(
                function(d) {
					// delete from origin
					var idx = self.list.models.indexOf(item);
                    self.list.models.splice(idx, 1);
                    
                    // set to index 0
                    self.list.model = self.list.models[0];
                    self.list.models[0].selected = true;
				},
				function(errResponse) {
					$log.error('Error while deleting Model. Error: ' + errResponse);
					toaster.pop('error', 'Error Caught', 'Error while deleting Model by Grup');
                }
            );
        }
	};
	
	self.edit = function(id) {
        //
    };
    
    self.select = function(item) {
        angular.forEach(self.list.models, function(model) {
            model.selected = false;
        });
        self.list.model = item;
        self.list.model.selected = true;
    }

    self.openConfirmDeleteByGrupModal = function (item) {
		var modalInstance = $modal.open({
		  templateUrl: 'confirmDeleteModalContent.html',
		  controller: 'ModalInstanceCtrl',
		  size: 'xs', // sm, lg
		  resolve: {
			msg: function () {
                return {ctrl: 'Master Grup', label: item.grup};
			}
		  }
		});
  
		modalInstance.result.then(function () {
		  self.remove('grup', item);
		}, function () {
		  $log.info('Modal dismissed at: ' + new Date());
		});
	};
	
	self.openConfirmDeleteByIdModal = function (item) {
		var modalInstance = $modal.open({
		  templateUrl: 'confirmDeleteModalContent.html',
		  controller: 'ModalInstanceCtrl',
		  size: 'xs', // sm, lg
		  resolve: {
			msg: function () {
                return {ctrl: 'Pilihan', label: item.field_01};
			}
		  }
		});
  
		modalInstance.result.then(function () {
		  self.remove('option', item);
		}, function () {
		  $log.info('Modal dismissed at: ' + new Date());
		});
    };
    
    self.editItem = function(item) {
        if(item && item.selected) {
            item.editing = true;
        }
    };

    self.doneEditing = function(item) {
        item.editing = false;
    };

    self.openFormModal = function (item) {
        var itemOrigin = angular.copy(item);
        var modalInstance = $modal.open({
            templateUrl: 'formPilihanModalContent.html',
            controller: 'PilihanFormModalInstanceCtrl',
            size: 'xs', // sm, lg
            resolve: {
                model: function () {
                    return item;
                }
            }
		});
  
		modalInstance.result.then(function () {
            // saveOrUpdate
		}, function () {
            angular.copy(itemOrigin, item);
            $log.info('Modal dismissed at: ' + new Date());
		});
    };

	// init function
    self.fetchAll();
    
    self.scope = $scope;
	window.self = self;
}]);

app.controller('PilihanFormModalInstanceCtrl', ['$scope', '$modalInstance', 'model', function($scope, $modalInstance, model) {
    $scope.model = model;
    
    $scope.ok = function () {
      $modalInstance.close($scope.model);
    };

    $scope.cancel = function () {
      $modalInstance.dismiss('cancel');
    };
}]);