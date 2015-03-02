
(function (angular, bleedHd) {

	function AssessmentEditController($scope, $location, $templateCache, AssessmentData, HeaderControl, DateHelper, FormWrapper, DomainConst, patient, assessment) {
		HeaderControl.hide();

		this.AssessmentData = AssessmentData;
		this.DomainConst = DomainConst;
		this.patient = patient;
		this.assessment = FormWrapper(assessment);
		this.$scope = $scope;
		this.$location = $location;
		this.$templateCache = $templateCache;

		// this is necessary for the FormWrapper to set up a 'copy' of the start_date property
		// since setDate and setTime would otherwise operate on the original value
		this.assessment.start_date = DateHelper.fromDate(this.assessment.start_date.date, true);
		this.startDate = DateHelper.fromDate(this.assessment.start_date.date, false);
		this.startTime = DateHelper.fromDate(this.assessment.start_date.date, true);

		this.isNew = (this.assessment.id === undefined);
		this.secondaryScoreTemplate = this.getSecondaryScoreTemplate();

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
				if (ctl.assessmentForm.$valid) {
					ctl.AssessmentData.saveAssessment(ctl.assessment.persist()).then(function () {
						ctl.$location.path('/patients/detail/' + ctl.patient.id).search('tab', 'assessments');
					});
				}
			},
			createAndStart: function (questionnaire) {
				var ctl = this;
				if (ctl.assessmentForm.$valid) {
					ctl.assessment.questionnaire = questionnaire;
					ctl.AssessmentData.saveAssessment(ctl.assessment).then(function (assessment) {
						ctl.$location.path(['/assessment', ctl.patient.id, assessment.id, 'start'].join('/'));
					});
				}
			},
			onDateChange: function () {
				this.assessment.start_date.setDate(this.startDate === undefined ? undefined : this.startDate.date);
			},
			onTimeChange: function () {
				this.assessment.start_date.setTime(this.startTime === undefined ? undefined : this.startTime.date);
			},
			getSecondaryScoreTemplate: function () {
				var view = bleedHd.getView('assessment', 'secondary-score-' + this.assessment.questionnaire);

				return this.$templateCache.get(view) ? view : '';
			},
		},
		{
			asName: 'ctlAssessment',
			templateUrl: bleedHd.getView('assessment', 'edit'),
			resolve: {
				patient: function ($route, PatientData) { return PatientData.getPatient($route.current.params.patientId); },
				assessment: function ($route, AssessmentData) {
					var params = $route.current.params;
					if (params.assessmentId === undefined) {
						return AssessmentData.newAssessment(params.patientId);
					} else {
						return AssessmentData.getAssessmentFull(params.patientId, params.assessmentId);
					}
				},
			},
		}
	);

})(angular, bleedHd);
