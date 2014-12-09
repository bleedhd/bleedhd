
(function (angular, bleedHd) {

	function PatientStatusEditController($scope, $location, $routeParams, patientData, patient) {
		this.$scope = $scope;
		this.$location = $location;
		this.patientData = patientData;
		this.patient = patient;

		if ($routeParams.statusId === undefined) {
			this.status = patientData.newStatus(patient);
		} else {
			var ctl = this;
			angular.forEach(patient.statuses, function (value) {
				if (value.id == $routeParams.statusId) {
					ctl.status = value;
				}
			});
		}
	}

	angular.extend(PatientStatusEditController.prototype, {
		save: function () {
			if (this.$scope.statusForm.$valid) {
				this.patientData.saveStatus(this.status);
				this.$location.path('/patients/detail/' + this.patient.id);
			}
		},
	});


	PatientStatusEditController.asName = 'ctlStatus';
	PatientStatusEditController.defaultTemplate = bleedHd.getView('patient', 'status-edit');
	PatientStatusEditController.resolve = {
		patient: function ($route, patientData) { return patientData.getPatient($route.current.params.patientId); },
	};

	bleedHd.registerController('patient', PatientStatusEditController);

})(angular, bleedHd);
