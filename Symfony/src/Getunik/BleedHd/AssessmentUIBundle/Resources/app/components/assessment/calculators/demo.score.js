
(function (angular) {

	angular.module('assessment').config(function (ScoringRegistryProvider) {

		ScoringRegistryProvider.registerTypeWithName('demo', null, function (parent) {
			return {
				ctor: function DemoScoreCalculator () { console.log('instantiating demo score calculator'); },
				members: {
					run: function (assessmentContext) {
						var result = {
							trueCount: 0,
						};

						console.log('runnin demo score calculator');
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
				},
			};
		});

	});

})(angular);
