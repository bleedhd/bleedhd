
(function (angular, bleedHd) {

	function PatientOverviewController($scope, patients) {
		this.patients = patients;
	}

	PatientOverviewController.asName = 'ctlPatients';
	PatientOverviewController.defaultTemplate = bleedHd.getView('patient', 'overview');
	PatientOverviewController.resolve = {
		patients: function (secureResource) { return secureResource('/api/patients/:patientId', { patientId: '@id' }).query(); },
	}

	bleedHd.registerController('patient', PatientOverviewController);

})(angular, bleedHd);
