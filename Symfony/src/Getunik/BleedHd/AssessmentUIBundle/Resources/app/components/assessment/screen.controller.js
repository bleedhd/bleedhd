
(function (angular, bleedHd) {

	function AssessmentScreenController($scope, $route, $location, $log, $q, $routeParams, $timeout, HeaderControl, context) {
		HeaderControl.hide();
		$scope.context = this.context = context;

		this.$scope = $scope;
		this.$location = $location;
		this.$timeout = $timeout;
		this.$log = $log;
		this.$q = $q;

		if ($route.current.params.screen === 'start') {
			this.$log.verbose('starting assessment', context.assessment.id);
			var firstScreen = context.questionnaire.getScreenByIndex(0);
			this.goToScreen(firstScreen);
		} else {
			this.screen = context.questionnaire.getScreenBySlug($route.current.params.screen);
		}

		var that = this;
		that.dirty = {};

		$scope.$on('q-response-changed', function (event, response) {
			that.dirty[response.id] = response;
			that.$log.debug('dirty response', response);
		});

		if (!!$routeParams.q) {
			this.scrollToQuestion($routeParams.q);
		}

		// this is the controlling counterpart of the questionLink directive
		that.$scope.$on('question-link-goto', angular.bind(that, that.goToQuestion));
	}

	bleedHd.registerController('assessment', AssessmentScreenController,
		{
			goNext: function () {
				var that = this,
					next = that.context.questionnaire.getScreenByIndex(that.screen.index + 1);

				that.saveModifiedResponses().then(function () {
					that.goToScreen(next);
				});
			},
			goPrev: function () {
				var that = this;
					prev = that.context.questionnaire.getScreenByIndex(that.screen.index - 1);

				that.saveModifiedResponses().then(function () {
					that.goToScreen(prev);
				});
			},
			goOverview: function () {
				var that = this;
				this.saveModifiedResponses().then(function () {
					that.$location.path(['/patients', that.context.patient.id, 'assessment/edit', that.context.assessment.id].join('/'));
				});
			},
			saveModifiedResponses: function () {
				var responsesToSave = $.map(this.dirty, function(val) { return val; });
				this.dirty = {};
				return responsesToSave.length > 0 ? this.context.saveResponses(responsesToSave) : this.$q.when(null);
			},
			saveCallback: function () {
				return angular.bind(this, this.saveModifiedResponses);
			},
			goToScreen: function (screen) {
				this.$location.path(['/assessment', this.context.patient.id, this.context.assessment.id, screen.urlSlug].join('/'));
			},
			scrollToQuestion: function (questionSlug) {
				// there doesn't seem to be reliable "view ready" detection, so we have to do some
				// interesting things here as described in http://lorenzmerdian.blogspot.de/2013/03/how-to-handle-dom-updates-in-angularjs.html
				this.$timeout(function () {
					var targetQuestion = $('#' + questionSlug);
					if (targetQuestion.length > 0) {
						$('html, body').animate({ scrollTop: (targetQuestion.offset().top) }, 'slow');
					}
				}, 0);
			},
			goToQuestion: function (event, val) {
				var slug = new this.context.questionnaire.Slug(val);

				if (slug.isDescendantOf(this.screen.slug)) {
					this.scrollToQuestion(slug.short);
				} else {
					this.$location.path(['/assessment', this.context.patient.id, this.context.assessment.id, slug.parent.short].join('/')).search('q', slug.short);
				}
			},
		},
		{
			asName: 'ctlScreen',
			templateUrl: bleedHd.getView('assessment', 'screen'),
			resolve: {
				context: function ($route, AssessmentContext) {
					var params = $route.current.params;
					return AssessmentContext(params.patientId, params.assessmentId);
				},
			},
		}
	);

})(angular, bleedHd);
