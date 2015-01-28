
(function (angular, bleedHd) {

	function PatientEditController($scope, $location, PatientData, patient) {
		this.PatientData = PatientData;
		this.patient = patient;
		this.$scope = $scope;
		this.$location = $location;

		$scope.patient_number = parseInt(patient.patient_number);
		$scope.$watch('patient_number', function (newValue) {
			patient.patient_number = numberToId(newValue);
		});

		$scope.upn = parseInt(patient.upn);
		$scope.$watch('upn', function (newValue) {
			patient.upn = numberToId(newValue);
		});
	}

	function numberToId(newValue) {
		return (newValue === undefined || newValue === 0 ? '' : newValue.toString());
	}

	bleedHd.registerController('patient', PatientEditController,
		{
			save: function () {
				var ctl = this;
				if (ctl.patientForm.$valid) {
					ctl.PatientData.savePatient(ctl.patient).then(function () {
						ctl.$location.path('/patients');
					});
				}
			},
		},
		{
			asName: 'ctlPatient',
			templateUrl: bleedHd.getView('patient', 'edit'),
			resolve: {
				patient: function($route, PatientData) {
					if ($route.current.params.patientId === undefined) {
						return PatientData.newPatient();
					} else {
						return PatientData.getPatient($route.current.params.patientId);
					}
				}
			},
		}
	);

})(angular, bleedHd);
