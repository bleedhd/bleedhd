
(function (angular, bleedHd) {

	function PatientStatusEditController($scope, $location, $routeParams, PatientData, HeaderControl, FormWrapper, patient) {
		var status;

		HeaderControl.disableLogout();

		this.$scope = $scope;
		this.$location = $location;
		this.PatientData = PatientData;
		this.patient = patient;

		if ($routeParams.statusId === undefined) {
			status = PatientData.newStatus(patient);
		} else {
			angular.forEach(patient.statuses, function (value) {
				if (value.id == $routeParams.statusId) {
					status = value;
				}
			});
		}
		this.status = FormWrapper(status);

		// Only allogeneic transplants can/should have a transplant source, so we reset the transplant source to empty
		// string for all transplant types except for allogeneic.
		var status = this.status;
		$scope.$watch('ctlStatus.status.transplant_type', function (newValue, oldValue) {
			if (status.transplant_type !== 'allogeneic') {
				status.transplant_source = '';
			}
			if (status.transplant_type !== 'other') {
				status.transplant_custom = '';
			}
		});
	}

	bleedHd.registerController('patient', PatientStatusEditController,
		{
			save: function () {
				var ctl = this;
				if (ctl.statusForm.$valid) {
					ctl.PatientData.saveStatus(ctl.status.persist()).then(function () {
						ctl.$location.path('/patients/detail/' + ctl.patient.id).search('tab', 'status');
					});
				}
			},
		},
		{
			asName: 'ctlStatus',
			templateUrl: bleedHd.getView('patient', 'status-edit'),
			resolve: {
				patient: function ($route, PatientData) { return PatientData.getPatient($route.current.params.patientId); },
			},
		}
	);


})(angular, bleedHd);
