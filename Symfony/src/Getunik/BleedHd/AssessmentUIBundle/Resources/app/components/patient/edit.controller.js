
(function (angular, bleedHd) {

	function PatientEditController($scope, patientData, patient) {
		this.patientData = patientData;
		this.patient = patient;
		this.$scope = $scope;
	}

	angular.extend(PatientEditController.prototype, {
		save: function () {
			if (this.$scope.patientForm.$valid) {
				this.patientData.savePatient(this.patient);
			}
		},
	});


	PatientEditController.asName = 'ctlPatient';
	PatientEditController.defaultTemplate = bleedHd.getView('patient', 'edit');
	PatientEditController.resolve = {
		patient: function(patientData) { return patientData.newPatient(); }
	}

	bleedHd.registerController('patient', PatientEditController);

})(angular, bleedHd);
