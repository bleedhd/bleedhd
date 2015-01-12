
(function (angular, bleedHd) {

	function AssessmentScreenController($scope, $route, $location, context) {
		$scope.context = this.context = context;

		this.$scope = $scope;
		this.$location = $location;

		if ($route.current.params.screen === 'start') {
			console.log('starting');
			var firstScreen = context.questionnaire.screensLinear[0].slug;
			$location.path(['/assessment', context.patient.id, context.assessment.id, firstScreen].join('/'));
		} else {
			this.screen = context.questionnaire.screens[$route.current.params.screen];
		}
	}

	bleedHd.registerController('assessment', AssessmentScreenController,
		{
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
