
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
			that.dirty[response.id] = response;
			console.log('dirty questions', that.dirty);
		});
	}

	bleedHd.registerController('assessment', AssessmentScreenController,
		{
			saveModifiedResponses: function () {
				var responsesToSave = $.map(this.dirty, function(val) { return val; });
				this.dirty = {};
				return this.context.saveResponses(responsesToSave);
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
