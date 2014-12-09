
(function (angular, bleedHd) {

	function PatientDetailController($scope, patientData, patient) {
		this.patientData = patientData;
		this.patient = patient;
	}

	angular.extend(PatientDetailController.prototype, {
	});


	PatientDetailController.asName = 'ctlPatient';
	PatientDetailController.defaultTemplate = bleedHd.getView('patient', 'detail');
	PatientDetailController.resolve = {
		patient: function ($route, patientData) { return patientData.getPatient($route.current.params.patientId); },
	};

	bleedHd.registerController('patient', PatientDetailController);

})(angular, bleedHd);
