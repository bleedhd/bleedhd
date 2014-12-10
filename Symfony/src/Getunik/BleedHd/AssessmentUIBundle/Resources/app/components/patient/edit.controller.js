
(function (angular, bleedHd) {

	function PatientEditController($scope, $location, patientData, patient) {
		this.patientData = patientData;
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
				if (this.$scope.patientForm.$valid) {
					this.patientData.savePatient(this.patient);
					this.$location.path('/patients');
				}
			},
		},
		{
			asName: 'ctlPatient',
			templateUrl: bleedHd.getView('patient', 'edit'),
			resolve: {
				patient: function($route, patientData) {
					if ($route.current.params.patientId === undefined) {
						return patientData.newPatient();
					} else {
						return patientData.getPatient($route.current.params.patientId);
					}
				}
			},
		}
	);

})(angular, bleedHd);
