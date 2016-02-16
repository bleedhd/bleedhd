
(function (angular, bleedHd) {

	function PatientEditController($scope, $location, PatientData, HeaderControl, FormWrapper, patient) {
		HeaderControl.disableLogout();

		this.PatientData = PatientData;
		this.patient = FormWrapper(patient);
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
				var ctl = this;
				if (ctl.patientForm.$valid) {
					ctl.PatientData.savePatient(ctl.patient.persist()).then(function () {
						ctl.$location.path(ctl.getReturnPath());
					});
				}
			},
			delete: function () {
				var ctl = this;
				ctl.PatientData.deletePatient(ctl.patient).then(function () {
					ctl.$location.path('/patients');
				});
			},
			getReturnPath: function () {
				return (this.isNew ? '/patients' : '/patients/detail/' + this.patient.id);
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
