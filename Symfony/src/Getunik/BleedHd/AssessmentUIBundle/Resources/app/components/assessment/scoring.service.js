
(function (angular, bleedHd) {

	function ScoringService($q, BleedApi) {
		this.$q = $q;
		this.BleedApi = BleedApi;

		this.calculators = {};
	}

	angular.extend(ScoringService.prototype, {
		registerCalculator: function (assessmentType, calculator) {
			this.calculators[assessmentType] = calculator;
		},
		getScore: function (assessmentContext) {
			var calculator = this.calculators[assessmentContext.assessment.questionnaire],
				score = calculator === undefined ? {} : calculator.run(assessmentContext);

			return score;
		}
	});

	angular.module('assessment')
		.service('Scoring', ScoringService);

})(angular, bleedHd);
