
(function (angular, bleedHd) {

	function ScoringService($q, BleedApi, ScoringRegistry) {
		this.$q = $q;
		this.ScoringRegistry = ScoringRegistry;
	}

	angular.extend(ScoringService.prototype, {
		getScore: function (assessmentContext) {
			var type = assessmentContext.assessment.questionnaire;

			if (this.ScoringRegistry.exists(type)) {
				return this.ScoringRegistry.instantiate(type).run(assessmentContext);
			}
		}
	});

	angular.module('assessment')
		.service('Scoring', ScoringService);

})(angular, bleedHd);
