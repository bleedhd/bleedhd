
(function (angular, bleedHd) {

	function PatientStatusDetailController($scope, $location, $routeParams, patientData, patient) {
		this.$scope = $scope;
		this.$location = $location;
		this.patientData = patientData;
		this.patient = patient;

		var ctl = this;
		angular.forEach(patient.statuses, function (value) {
			if (value.id == $routeParams.statusId) {
				ctl.status = value;
			}
		});
	}

	angular.extend(PatientStatusDetailController.prototype, {
		save: function () {
			if (this.$scope.statusForm.$valid) {
				this.patientData.saveStatus(this.status);
				this.$location.path('/patients/detail/' + this.patient.id);
			}
		},
	});


	PatientStatusDetailController.asName = 'ctlStatus';
	PatientStatusDetailController.defaultTemplate = bleedHd.getView('patient', 'status-edit');
	PatientStatusDetailController.resolve = {
		patient: function ($route, patientData) { return patientData.getPatient($route.current.params.patientId); },
	};

	bleedHd.registerController('patient', PatientStatusDetailController);

})(angular, bleedHd);
