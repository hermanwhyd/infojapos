'use strict';

app.controller('PilihanController', ['$scope', '$http', '$filter', '$stateParams', '$log', '$modal', 'toaster', 'CommonService', function($scope, $http, $filter, $stateParams, $log, $modal, toaster, CommonService) {
	var self = this;
	
	//self.models = [];
    self.lPromise;
    self.list = {models: [], model: {options:[]}};
	self.msgConfirmDelete = {ctrl: 'Grup stt_nikah', label: 'Tidak Menikah'};

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
	
	self.remove = function(item) {
		console.log('id to be deleted', item.id);
		var idx = self.list.models.indexOf(item);
		self.list.models.splice(idx, 1);
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
        //
    };
    
    self.select = function(item) {
        angular.forEach(self.list.models, function(model) {
            model.selected = false;
        });
        self.list.model = item;
        self.list.model.selected = true;
    }
	
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
    
    self.scope = $scope;
	window.self = self;
}]);

app.controller('PilihanFormController', ['$scope', '$log', '$stateParams', 'toaster', 'CommonService', 'PilihanService', function($scope, $log, $stateParams, toaster, CommonService, thisService) {
    var self = this;
    var modelOrigin = {};

    self.form = {model: {}, options: {}};
    self.lPromise;

    // initiation model, -1 is new
    self.fetchOneOrNew = function() {
        var id = angular.isDefined($stateParams.id) ? $stateParams.id : -1;
        self.lPromise = CommonService.fetchOne(id)
            .then(
                function(d) {
                    modelOrigin = d;
                    self.form.model = angular.copy(modelOrigin);
                },
                function(errResponse) {
                    $log.error('Error while fetching Model. Reason: ' + errResponse.statusText);
                    toaster.pop('error', errResponse.data.message);
                }
            );
    };

    // init function
    self.fetchOneOrNew();
    
    self.create = function(form) {
        thisService.createJamaah(form)
            .then(
                self.form.model,
                function(errResponse) {
                    $log.error('Error while creating User.');
                }
            );
    };

    self.update = function(form, id) {
        self.lPromise = thisService.update(form, id)
            .then(
                self.fetchAllJamaah,
                function(errResponse) {
                    $log.error('Error while updating User.');
                }
            );
    };

    self.deleteJamaah = function(id) {
        self.lPromise = thisService.deleteJamaah(id)
            .then(
                self.fetchAll,
                function(errResponse) {
                    $log.error('Error while deleting User.');
                }
            );
    };

    self.submit = function() {
        toaster.pop('info', "Hallo", "Hei");
        if (self.form.model.id == null) {
            $log.log('Saving New Jamaah', self.form.model);
            // self.createJamaah(self.form.model);
        } else {
            // self.updateJamaah(self.form.model, self.form.model.id);
            $log.log('User updated with id ', self.form.model.id);
        }
        self.revert();
    };

    self.canSubmit = function() {
        return !angular.equals(self.form.model, modelOrigin) || !$scope.main_form.$pristine;
    }

    self.revert = function() {
        self.form.model = angular.copy(modelOrigin);
        $scope.main_form.$setPristine();
    };

    self.canRevert = function() {
        return !angular.equals(self.form.model, modelOrigin) || !$scope.main_form.$pristine;
    }

    // for development only
    self.scope = $scope;
    window.self = self;
}]);