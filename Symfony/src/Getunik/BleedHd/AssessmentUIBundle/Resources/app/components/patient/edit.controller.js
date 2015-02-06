
(function (angular, bleedHd) {

	function PatientEditController($scope, $location, PatientData, HeaderControl, patient) {
		HeaderControl.disableLogout();

		this.PatientData = PatientData;
		this.patient = patient;
		this.isNew = (this.patient.id === undefined);
		this.$scope = $scope;
		this.$location = $location;
	}

	function numberToId(newValue) {
		return (newValue === undefined || newValue === 0 ? '' : newValue.toString());
	}

	bleedHd.registerController('patient', PatientEditController,
		{
			save: function () {
				var ctl = this,
					target = (ctl.isNew ? '/patients' : '/patients/detail/' + this.patient.id);
				if (ctl.patientForm.$valid) {
					ctl.PatientData.savePatient(ctl.patient).then(function () {
						ctl.$location.path(target);
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
