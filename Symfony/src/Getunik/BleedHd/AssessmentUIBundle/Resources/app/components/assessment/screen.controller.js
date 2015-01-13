
(function (angular, bleedHd) {

	function AssessmentScreenController($scope, $route, $location, context) {
		$scope.context = this.context = context;

		this.$scope = $scope;
		this.$location = $location;

		if ($route.current.params.screen === 'start') {
			console.log('starting');
			var firstScreen = context.questionnaire.getFirstScreenSlug();
			$location.path(['/assessment', context.patient.id, context.assessment.id, firstScreen].join('/'));
		} else {
			this.screen = context.questionnaire.getScreenBySlug($route.current.params.screen);
		}

		var that = this;
		that.dirty = {};

		$scope.$on('response-changed', function (event, response) {
			that.dirty[response.question_slug] = response;
			console.log('dirty questions', that.dirty);
		});
	}

	bleedHd.registerController('assessment', AssessmentScreenController,
		{
			getResponseForQuestion: function (slug) {
				// TODO: fetch actual existing response if available
				return {
					assessment_id: this.context.assessment.id,
					question_slug: slug,
					result: { value: null, meta: 'nya' },
				};
			},
			saveModifiedResponses: function () {
				return this.context.saveResponses($.map(this.dirty, function(val) { return val; }));
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
