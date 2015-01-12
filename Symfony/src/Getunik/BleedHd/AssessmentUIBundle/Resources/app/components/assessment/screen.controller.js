
(function (angular, bleedHd) {

	function AssessmentScreenController($scope, $location, context) {
		$scope.context = this.context = context;

		this.$scope = $scope;
		this.$location = $location;
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
