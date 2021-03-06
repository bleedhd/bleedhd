
(function (angular, bleedHd) {

	function Slug(slug, parent) {
		if (parent === undefined) {
			var segments = slug.split('.'),
				slug = segments.pop();

			parent = segments.reduce(function (last, current) {
				return new Slug(current, last);
			}, undefined);
		}

		this.parent = parent;
		this.full = (parent === undefined ? [] : [parent.full]).concat(slug === undefined ? [] : [slug]).join('.');
		this.short = slug;
	}

	angular.extend(Slug.prototype, {
		getChild: function (slug) {
			return new Slug(slug, this);
		},
		isDescendantOf: function (other) {
			var current = this;

			while (current !== undefined) {
				if (current.full === other.full)
					return true;

				current = current.parent;
			}

			return false;
		},
	});


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
		// expose the Slug class to everybody working with questionnaires
		Slug: Slug,
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
			var that = this, screenObj, chapterSlug, sectionSlug, screenSlug, screenUrlSlug, screenIndex = 0;

			this.metaAnswers = yamlData.meta_answers;
			this.title = yamlData.title;
			this.quickLinks = yamlData.quick_links;
			this.version = yamlData.version || 'unknown';

			angular.forEach(yamlData.chapters, function (chapter) {
				chapterSlug = that.rootSlug.getChild(chapter.slug);

				angular.forEach(chapter.sections, function (section) {
					sectionSlug = chapterSlug.getChild(section.slug);

					angular.forEach(section.screens, function (screen) {
						screenSlug = sectionSlug.getChild(screen.slug);
						// the slug used in a screen's URL can be different from the one used in
						// the question slug hierarchy since the screen short-slugs have to be
						// unique across a questionnaire
						screenUrlSlug = !screen.url_slug ? screen.slug : screen.url_slug;

						var screenObj = {
							chapter: chapterSlug,
							section: sectionSlug,
							slug: screenSlug,
							urlSlug: screenUrlSlug,
							index: screenIndex++,
							questions: screen.questions,
						};

						angular.forEach(screenObj.questions, function (question, index) {
							question.index = that.questionCount + index + 1;
							that._processQuestion(screenSlug, question);
						});
						// the sub-questions of multi-questions don't "count"
						that.questionCount += screenObj.questions.length;

						that.screens[screenUrlSlug] = screenObj;
						that.screensLinear.push(screenObj);
					});
				});
			});
		},
		_processQuestion: function (parentSlug, question) {
			var that = this;

			question.questionnaire = that;
			question.slug = parentSlug.getChild(question.slug);
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
