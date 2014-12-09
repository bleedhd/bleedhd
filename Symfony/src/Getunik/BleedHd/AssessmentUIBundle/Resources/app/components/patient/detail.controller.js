
(function (angular, bleedHd) {

	function PatientDetailController($scope, patientData, patient, statuses) {
		this.patientData = patientData;
		this.patient = patient;
		console.log(statuses);
	}

	angular.extend(PatientDetailController.prototype, {
	});


	PatientDetailController.asName = 'ctlPatient';
	PatientDetailController.defaultTemplate = bleedHd.getView('patient', 'detail');
	PatientDetailController.resolve = {
		patient: function ($route, patientData) { return patientData.getPatient($route.current.params.patientId); },
		statuses: function ($route, patientData) { return patientData.getStatuses($route.current.params.patientId); },
	};

	bleedHd.registerController('patient', PatientDetailController);

})(angular, bleedHd);
