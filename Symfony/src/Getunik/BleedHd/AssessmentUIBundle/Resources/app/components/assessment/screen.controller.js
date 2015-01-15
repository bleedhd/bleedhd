
(function (angular, bleedHd) {

	function AssessmentScreenController($scope, $route, $location, context) {
		$scope.context = this.context = context;

		this.$scope = $scope;
		this.$location = $location;

		if ($route.current.params.screen === 'start') {
			console.log('starting');
			var firstScreen = context.questionnaire.getScreenByIndex(0);
			this.goToScreen(firstScreen);
		} else {
			this.screen = context.questionnaire.getScreenBySlug($route.current.params.screen);
		}

		var that = this;
		that.dirty = {};

		$scope.$on('q-response-changed', function (event, response) {
			that.dirty[response.id] = response;
			console.log('dirty questions', that.dirty);
		});
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
				this.saveModifiedResponses();
				this.$location.path(['/patients', this.context.patient.id, 'assessment/edit', this.context.assessment.id].join('/'));
			},
			saveModifiedResponses: function () {
				var responsesToSave = $.map(this.dirty, function(val) { return val; });
				this.dirty = {};
				return responsesToSave.length > 0 ? this.context.saveResponses(responsesToSave) : null;
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
