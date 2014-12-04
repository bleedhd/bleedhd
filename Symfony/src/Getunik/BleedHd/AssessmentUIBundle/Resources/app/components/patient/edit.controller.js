
(function (angular, bleedHd) {

	function PatientEditController($scope, patient) {
		this.test = 'test';
		this.patient = patient;
	}

	angular.extend(PatientEditController.prototype, {
	});


	PatientEditController.asName = 'ctlPatient';
	PatientEditController.defaultTemplate = bleedHd.getView('patient', 'edit');
	PatientEditController.resolve = {
		patient: function(patientData) { return patientData.newPatient(); }
	}

	bleedHd.registerController('patient', PatientEditController);

})(angular, bleedHd);
