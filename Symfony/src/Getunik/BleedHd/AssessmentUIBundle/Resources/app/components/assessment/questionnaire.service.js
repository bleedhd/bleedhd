
(function (angular, bleedHd) {

	function Slug(slug, parent) {
		this.full = (parent === undefined ? [] : [parent.full]).concat(slug === undefined ? [] : [slug]).join('.');
		this.short = slug;
	}


	function Questionnaire(yamlData) {
		this.screens = {};
		this.screensLinear = [];

		this.processYaml(yamlData);
	}

	angular.extend(Questionnaire.prototype, {
		getScreenBySlug: function (slug) {
			return this.screens[slug];
		},
		getScreenByIndex: function (index) {
			return this.screensLinear[index];
		},
		getScreenCount: function () {
			return this.screensLinear.length;
		},
		processYaml: function (yamlData) {
			var that = this, screenObj, chapterSlug, sectionSlug, screenSlug, screenIndex = 0;

			angular.forEach(yamlData.chapters, function (chapter) {
				chapterSlug = new Slug(chapter.slug);

				angular.forEach(chapter.sections, function (section) {
					sectionSlug = new Slug(section.slug, chapterSlug);

					angular.forEach(section.screens, function (screen) {
						screenSlug = new Slug(screen.slug, sectionSlug);

						var screenObj = {
							chapter: chapterSlug,
							section: sectionSlug,
							slug: screenSlug,
							index: screenIndex++,
							questions: screen.questions,
						};

						angular.forEach(screenObj.questions, function (question) {
							question.slug = new Slug(question.slug, screenSlug);
						});

						that.screens[screen.slug] = screenObj;
						that.screensLinear.push(screenObj);
					});
				});
			});
		},
	});


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
			return new Questionnaire(yamlData);
		},
	});


	angular.module('assessment')
		.service('QuestionnaireData', QuestionnaireDataService);

})(angular, bleedHd);
