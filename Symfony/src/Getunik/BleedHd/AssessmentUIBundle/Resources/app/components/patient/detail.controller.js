
(function (angular, bleedHd) {

	function PatientDetailController($scope, patientData, patient) {
		this.patientData = patientData;
		this.patient = patient;
	}

	bleedHd.registerController('patient', PatientDetailController,
		{},
		{
			asName: 'ctlPatient',
			templateUrl: bleedHd.getView('patient', 'detail'),
			resolve: {
				patient: function ($route, patientData) { return patientData.getPatient($route.current.params.patientId); },
			},
		}
	);

})(angular, bleedHd);
