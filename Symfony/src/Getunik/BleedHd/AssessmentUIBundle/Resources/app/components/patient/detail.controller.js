
(function (angular, bleedHd) {

	function PatientDetailController($scope, PatientData, patient) {
		this.PatientData = PatientData;
		this.patient = patient;
	}

	bleedHd.registerController('patient', PatientDetailController,
		{},
		{
			asName: 'ctlPatient',
			templateUrl: bleedHd.getView('patient', 'detail'),
			resolve: {
				patient: function ($route, PatientData) { return PatientData.getPatient($route.current.params.patientId); },
			},
		}
	);

})(angular, bleedHd);
