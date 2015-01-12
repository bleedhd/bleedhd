
(function (angular, bleedHd) {

	function QuestionnaireDataService($q, BleedApi) {
		this.$q = $q;
		this.BleedApi = BleedApi;
		this.questionnaires = BleedApi.all('questionnaires');
	}

	angular.extend(QuestionnaireDataService.prototype, {
		get: function (name) {
			var that = this;
			return this.questionnaires.get(name).then(function (yamlData) {
				return that.processYaml(yamlData);
			});
		},
		processYaml: function (yamlData) {
			var screenObj,
				screenIndex = 0,
				questionnaire = {
					screens: {},
					screensLinear: [],
				};

			angular.forEach(yamlData.questions, function (chapterData, chapter) {
				angular.forEach(chapterData, function (sectionData, section) {
					angular.forEach(sectionData, function (screenData, screen) {
						var screenObj = {
							chapter: chapter,
							section: section,
							slug: screen,
							index: screenIndex++,
							questions: screenData,
						};

						questionnaire.screens[screen] = screenObj;
						questionnaire.screensLinear.push(screenObj);
					});
				});
			});

			return questionnaire;
		},
	});


	angular.module('assessment')
		.service('QuestionnaireData', QuestionnaireDataService);

})(angular, bleedHd);
