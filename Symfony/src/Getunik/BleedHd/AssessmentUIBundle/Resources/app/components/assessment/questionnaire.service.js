
(function (angular, bleedHd) {

	function Slug(slug, parent) {
		this.full = (parent === undefined ? [] : [parent.full]).concat(slug === undefined ? [] : [slug]).join('.');
		this.short = slug;
	}


	function Questionnaire(name, yamlData) {
		this.screens = {};
		this.screensLinear = [];
		this.multiQuestions = {};
		this.questions = [];
		this.rootSlug = new Slug(yamlData.slug || name);
		this.questionCount = 0;

		this._processYaml(yamlData);
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
		isMultiQuestion: function (slug) {
			return this.multiQuestions[slug.full] !== undefined;
		},
		getMultiQuestionChildSlugs: function (slug) {
			return this.multiQuestions[slug.full];
		},
		_processYaml: function (yamlData) {
			var that = this, screenObj, chapterSlug, sectionSlug, screenSlug, screenIndex = 0;

			this.metaAnswers = yamlData.meta_answers;
			this.title = yamlData.title;
			this.quickLinks = yamlData.quick_links;
			this.version = yamlData.version || 'unknown';

			angular.forEach(yamlData.chapters, function (chapter) {
				chapterSlug = new Slug(chapter.slug, that.rootSlug);

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

						angular.forEach(screenObj.questions, function (question, index) {
							question.index = that.questionCount + index + 1;
							that._processQuestion(screenSlug, question);
						});
						// the sub-questions of multi-questions don't "count"
						that.questionCount += screenObj.questions.length;

						that.screens[screen.slug] = screenObj;
						that.screensLinear.push(screenObj);
					});
				});
			});
		},
		_processQuestion: function (parentSlug, question) {
			var that = this;

			question.questionnaire = that;
			question.slug = new Slug(question.slug, parentSlug);
			question.globalMeta = that.metaAnswers;
			if (question.type === 'multi') {
				that.multiQuestions[question.slug.full] = [];
				angular.forEach(question.questions, function (child) { that._processQuestion(question.slug, child); });
			} else {
				that.questions.push(question);
				if (that.multiQuestions[parentSlug.full] !== undefined) {
					that.multiQuestions[parentSlug.full].push(question.slug);
				}
			}
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
				return new Questionnaire(name, yamlData);
			});
		},
	});


	angular.module('assessment')

		.service('RawQuestionnaireData', QuestionnaireDataService)

		.service('QuestionnaireData', function (CachingWrapper, RawQuestionnaireData) {
			return CachingWrapper(RawQuestionnaireData, [
					{
						func: 'get',
						key: function (name) { return name; },
					},
				]
			);
		})

	;

})(angular, bleedHd);
