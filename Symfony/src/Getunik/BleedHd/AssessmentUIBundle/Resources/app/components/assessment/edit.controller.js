
(function (angular, bleedHd) {

	function AssessmentEditController($location, $templateCache, BleedHdConfig, AssessmentData, HeaderControl, patient, assessment, group) {
		HeaderControl.hide();

		this.BleedHdConfig = BleedHdConfig;
		this.AssessmentData = AssessmentData;
		this.patient = patient;
		this.assessment = assessment;
		this.$location = $location;
		this.$templateCache = $templateCache;

		this.secondaryScoreTemplate = this.getSubTemplate('secondary-score-' + this.assessment.questionnaire);
	}

	bleedHd.registerController('assessment', AssessmentEditController,
		{
			delete: function () {
				var ctl = this;
				ctl.AssessmentData.deleteAssessment(ctl.assessment).then(function () {
					ctl.$location.path(['/patients', 'detail', ctl.patient.id].join('/'));
				});
			},
			getSubTemplate: function (name) {
				var view = bleedHd.getView('assessment', name);
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
					return AssessmentData.getAssessmentFull(params.patientId, params.assessmentId);
				},
				group: function ($route) { return $route.current.params.group; },
			},
		}
	);

})(angular, bleedHd);
