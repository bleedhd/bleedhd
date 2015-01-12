
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
			var screenObj, chapterSlug, sectionSlug, screenSlug,
				screenIndex = 0,
				questionnaire = {
					screens: {},
					screensLinear: [],
				};

			angular.forEach(yamlData.chapters, function (chapter) {
				chapterSlug = (chapter.slug === undefined ? [] : [chapter.slug]);

				angular.forEach(chapter.sections, function (section) {
					sectionSlug = chapterSlug.concat(section.slug === undefined ? [] : [section.slug]);

					angular.forEach(section.screens, function (screen) {
						screenSlug = sectionSlug.concat(screen.slug === undefined ? [] : [screen.slug]);

						var screenObj = {
							chapter: chapter.slug,
							section: section.slug,
							slug: screenSlug.join('.'),
							index: screenIndex++,
							questions: screen.questions,
						};

						angular.forEach(screenObj.questions, function (question) {
							question.slug = screenSlug.concat([question.slug]).join('.');
						});

						questionnaire.screens[screen.slug] = screenObj;
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
