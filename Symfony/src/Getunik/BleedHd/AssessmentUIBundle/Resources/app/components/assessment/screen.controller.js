
(function (angular, bleedHd) {

	function AssessmentScreenController($scope, $location, AssessmentData, patientId, assessment) {
		this.AssessmentData = AssessmentData;
		this.patientId = patientId;
		this.assessment = assessment;
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
				patientId: function ($route) { return $route.current.params.patientId; },
				assessment: function ($route, AssessmentData) {
					var params = $route.current.params;
					return AssessmentData.getAssessment(params.patientId, params.assessmentId);
				},
			},
		}
	);

})(angular, bleedHd);
