
(function (angular, bleedHd) {

	function PatientOverviewController($scope, patients) {
		this.patients = patients;
	}

	PatientOverviewController.asName = 'ctlPatients';
	PatientOverviewController.defaultTemplate = bleedHd.getView('patient', 'overview');
	PatientOverviewController.resolve = {
		patients: function (patientData) { return patientData.getPatients(); },
	}

	bleedHd.registerController('patient', PatientOverviewController);

})(angular, bleedHd);
