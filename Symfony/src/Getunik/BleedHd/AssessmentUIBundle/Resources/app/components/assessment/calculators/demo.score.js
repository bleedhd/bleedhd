
(function (angular, bleedHd) {

	var demoCalculator = {
		run: function (assessmentContext) {
			var result = {
				trueCount: 0,
			};

			angular.forEach(assessmentContext.questionnaire.questions, function (question) {
				var response = assessmentContext.getResponseForQuestion(question.slug),
					data;

				if (response !== undefined) {
					data = response.result.data;
					if (data !== undefined && data !== null) {
						if (data.value === true) {
							result.trueCount++;
						}
					}
				}
			});

			return result;
		},
	};

	angular.module('assessment').run(function (Scoring) {
		Scoring.registerCalculator('demo', demoCalculator);
	});

})(angular, bleedHd);
