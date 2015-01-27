
(function (angular, bleedHd) {

	function AssessmentEditController($scope, $location, AssessmentData, patientId, assessment) {
		this.AssessmentData = AssessmentData;
		this.patientId = patientId;
		this.assessment = assessment;
		this.$scope = $scope;
		this.$location = $location;

		this.startDate = new Date(this.assessment.start_date.date);
		this.startTime = new Date(this.assessment.start_date.date);

		this.isNew = (this.assessment.id === undefined);

		// $scope.patient_number = parseInt(patient.patient_number);
		// $scope.$watch('patient_number', function (newValue) {
		// 	patient.patient_number = numberToId(newValue);
		// });
	}

	function numberToId(newValue) {
		return (newValue === undefined || newValue === 0 ? '' : newValue.toString());
	}

	bleedHd.registerController('assessment', AssessmentEditController,
		{
			save: function () {
				var ctl = this;
				if (ctl.$scope.assessmentForm.$valid) {
					ctl.AssessmentData.saveAssessment(ctl.assessment).then(function () {
						ctl.$location.path('/patients/detail/' + ctl.patientId);
					});
				}
			},
			createAndStart: function (questionnaire) {
				var ctl = this;
				if (ctl.$scope.assessmentForm.$valid) {
					ctl.assessment.questionnaire = questionnaire;
					ctl.AssessmentData.saveAssessment(ctl.assessment).then(function (assessment) {
						ctl.$location.path(['/assessment', ctl.patientId, assessment.id, 'start'].join('/'));
					});
				}
			},
			onDateChange: function () {
				this.assessment.start_date.setDate(this.startDate);
			},
			onTimeChange: function () {
				this.assessment.start_date.setTime(this.startTime);
			},
		},
		{
			asName: 'ctlAssessment',
			templateUrl: bleedHd.getView('assessment', 'edit'),
			resolve: {
				patientId: function ($route) { return $route.current.params.patientId; },
				assessment: function ($route, AssessmentData) {
					var params = $route.current.params;
					if (params.assessmentId === undefined) {
						return AssessmentData.newAssessment(params.patientId);
					} else {
						return AssessmentData.getAssessment(params.patientId, params.assessmentId);
					}
				},
			},
		}
	);

})(angular, bleedHd);
