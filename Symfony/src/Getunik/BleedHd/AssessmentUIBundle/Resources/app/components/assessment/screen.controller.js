
(function (angular, bleedHd) {

	function AssessmentScreenController($scope, $route, $location, $log, $q, $routeParams, $timeout, HeaderControl, context) {
		HeaderControl.hide();
		$scope.context = this.context = context;

		this.$scope = $scope;
		this.$location = $location;
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
			// there doesn't seem to be reliable "view ready" detection, so we have to do some
			// interesting things here as described in http://lorenzmerdian.blogspot.de/2013/03/how-to-handle-dom-updates-in-angularjs.html
			$timeout(function () {
				var targetQuestion = $('#' + $routeParams.q);
				if (targetQuestion.length > 0) {
					$('html, body').animate({ scrollTop: (targetQuestion.offset().top) }, 'slow');
				}
			}, 0);
		}
	}

	bleedHd.registerController('assessment', AssessmentScreenController,
		{
			goNext: function () {
				var next = this.context.questionnaire.getScreenByIndex(this.screen.index + 1);
				this.saveModifiedResponses();
				this.goToScreen(next);
			},
			goPrev: function () {
				var prev = this.context.questionnaire.getScreenByIndex(this.screen.index - 1);
				this.saveModifiedResponses();
				this.goToScreen(prev);
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
			goToScreen: function (screen) {
				this.$location.path(['/assessment', this.context.patient.id, this.context.assessment.id, screen.slug.short].join('/'));
			},
		},
		{
			asName: 'ctlScreen',
			templateUrl: bleedHd.getView('assessment', 'screen'),
			resolve: {
				context: function ($route, AssessmentContext) {
					var params = $route.current.params;
					return AssessmentContext.load(params.patientId, params.assessmentId);
				},
			},
		}
	);

})(angular, bleedHd);
