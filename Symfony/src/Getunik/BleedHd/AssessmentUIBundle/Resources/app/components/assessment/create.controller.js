
(function (angular, bleedHd) {

	function AssessmentCreateController($location, $templateCache, BleedHdConfig, AssessmentData, HeaderControl, patient, assessment, group) {
		HeaderControl.hide();

		this.$location = $location;
		this.$templateCache = $templateCache;
		this.BleedHdConfig = BleedHdConfig;
		this.AssessmentData = AssessmentData;

		this.patient = patient;
		this.assessment = assessment;

		this.assessmentGroupCreateTemplate = this.getSubTemplate('group-create-' + group);
	}

	bleedHd.registerController('assessment', AssessmentCreateController,
		{
			createAndStart: function (questionnaire) {
				var ctl = this;
				// set the questionnaire for the assessment creation
				ctl.assessment.questionnaire = questionnaire;
				ctl.formController.trySave().then(function (assessment) {
					if (assessment !== null) {
						ctl.$location.path(['/assessment', ctl.patient.id, assessment.id, 'start'].join('/'));
					}
				});
			},
			getSubTemplate: function (name) {
				var view = bleedHd.getView('assessment', name);
				return this.$templateCache.get(view) ? view : '';
			},
			supportsType: function (type) {
				var allowed = false;

				angular.forEach(this.BleedHdConfig.allowed_assessment_types, function (group) {
					allowed = allowed || (group.indexOf(type) >= 0);
				});

				return allowed || this.BleedHdConfig.feature.allow_custom_assessments;
			},
			registerForm: function (formController) {
				this.formController = formController;
			},
		},
		{
			asName: 'ctlAssessment',
			templateUrl: bleedHd.getView('assessment', 'create'),
			resolve: {
				patient: function ($route, PatientData) { return PatientData.getPatient($route.current.params.patientId); },
				assessment: function ($route, AssessmentData) { return AssessmentData.newAssessment($route.current.params.patientId); },
				group: function ($route) { return $route.current.params.group; },
			},
		}
	);

})(angular, bleedHd);
