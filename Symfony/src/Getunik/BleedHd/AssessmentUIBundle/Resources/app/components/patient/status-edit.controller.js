
(function (angular, bleedHd) {

	function PatientStatusEditController($scope, $location, $routeParams, patientData, patient) {
		this.$scope = $scope;
		this.$location = $location;
		this.patientData = patientData;
		this.patient = patient;

		if ($routeParams.statusId === undefined) {
			this.status = patientData.newStatus(patient);
		} else {
			var ctl = this;
			angular.forEach(patient.statuses, function (value) {
				if (value.id == $routeParams.statusId) {
					ctl.status = value;
				}
			});
		}

		// Only allogenic transplants can/should have a transplant source, so we reset the transplant source to empty
		// string for all transplant types except for allogenic.
		var status = this.status;
		$scope.$watch('ctlStatus.status.transplant_type', function (newValue, oldValue) {
			if (status.transplant_type !== 'allogenic') {
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
				if (ctl.$scope.statusForm.$valid) {
					ctl.patientData.saveStatus(ctl.status).then(function () {
						ctl.$location.path('/patients/detail/' + ctl.patient.id);
					});
				}
			},
		},
		{
			asName: 'ctlStatus',
			templateUrl: bleedHd.getView('patient', 'status-edit'),
			resolve: {
				patient: function ($route, patientData) { return patientData.getPatient($route.current.params.patientId); },
			},
		}
	);


})(angular, bleedHd);
