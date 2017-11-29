'use strict';

app.controller('JamaahController', ['$scope', '$log', '$stateParams', 'toaster', 'CommonService', 'JamaahService', function($scope, $log, $stateParams, toaster, CommonService, JamaahService) {
    var self = this;
    var modelOrigin = {};
    self.jamaah = {};

    // initiation model, -1 is new
    self.fetchOneOrNew = function() {
        var id = angular.isDefined($stateParams.id) ? $stateParams.id : -1;
        CommonService.fetchOne(id)
            .then(
                function(d) {
                    modelOrigin = d;
                    self.jamaah = angular.copy(modelOrigin);
                },
                function(errResponse) {
                    $log.error('Error while fetching Currencies. Reason: ' + errResponse.statusText);
                    toaster.pop('error', errResponse.data.message);
                }
            );
    };

    self.createJamaah = function(jamaah) {
        JamaahService.createJamaah(jamaah)
            .then(
                self.jamaah,
                function(errResponse) {
                    $log.error('Error while creating User.');
                }
            );
    };

    self.updateJamaah = function(jamaah, id) {
        JamaahService.updateJamaah(jamaah, id)
            .then(
                self.fetchAllJamaah,
                function(errResponse) {
                    $log.error('Error while updating User.');
                }
            );
    };

    self.deleteJamaah = function(id) {
        JamaahService.deleteJamaah(id)
            .then(
                self.fetchAllJamaah,
                function(errResponse) {
                    $log.error('Error while deleting User.');
                }
            );
    };

    self.fetchOneOrNew();

    self.submit = function() {
        toaster.pop('info', "Hallo", "Hei");
        if (self.jamaah.id == null) {
            $log.log('Saving New Jamaah', self.jamaah);
            // self.createJamaah(self.jamaah);
        } else {
            // self.updateJamaah(self.jamaah, self.jamaah.id);
            $log.log('User updated with id ', self.id);
        }
        self.reset();
    };

    self.edit = function(id) {
        $log.log('id to be edited', id);
        for (var i = 0; i < self.jamaahList.length; i++) {
            if (self.jamaahList[i].id == id) {
                self.jamaah = angular.copy(self.jamaahList[i]);
                break;
            }
        }
    };

    self.remove = function(id) {
        $log.log('id to be deleted', id);
        for (var i = 0; i < self.jamaahList.length; i++) {
            if (self.jamaahList[i].id == id) {
                self.reset();
                break;
            }
        }
        self.deleteJamaah(id);
    };

    self.reset = function() {
        self.user = angular.copy(modelOrigin);
        // $scope.myForm.$setPristine(); //reset Form
    };

    // for development only
    window.self = self;
}]);