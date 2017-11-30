'use strict';

app.controller('JamaahController', ['$scope', '$log', '$stateParams', 'toaster', 'CommonService', 'JamaahService', function($scope, $log, $stateParams, toaster, CommonService, JamaahService) {
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

    self.fetchPilihan = function() {
        self.lPromise = CommonService.fetchPilihan(['status_rumah','keahlian'])
        .then(
            function(d) {
                self.form.options = d;
            },
            function(errResponse) {
                $log.error('Error while fetching Pilihan. Reason: ' + errResponse.statusText);
                toaster.pop('error', errResponse.data.message);
            }
        );
    };

    // init function
    self.fetchPilihan();
    self.fetchOneOrNew();
    
    self.createJamaah = function(form) {
        JamaahService.createJamaah(form)
            .then(
                self.form.model,
                function(errResponse) {
                    $log.error('Error while creating User.');
                }
            );
    };

    self.updateJamaah = function(form, id) {
        self.lPromise = JamaahService.updateJamaah(form, id)
            .then(
                self.fetchAllJamaah,
                function(errResponse) {
                    $log.error('Error while updating User.');
                }
            );
    };

    self.deleteJamaah = function(id) {
        self.lPromise = JamaahService.deleteJamaah(id)
            .then(
                self.fetchAllJamaah,
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