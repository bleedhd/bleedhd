
(function (angular, bleedHd) {

	function AssessmentQuestionController($scope) {
		this.question = $scope.question();
		this.response = $scope.response();
		this.result = this.response.result;

		var that = this;

		$scope.$on('result-changed', function (event, result) {
			that.result.meta = null;
			$scope.$emit('response-changed', that.response);
		});

		$scope.$watch('questionCtl.result.meta', function (newValue, oldValue) {
			if (newValue !== oldValue && newValue !== null) {
				that.result.value = null;
			}
		});
	}

	angular.extend(AssessmentQuestionController.prototype, {
	});


	angular.module('question')
		.directive('question', function () {
			return {
				templateUrl: bleedHd.getView('question', 'container'),
				restrict: 'E',
				scope: {
					question: '&data',
					response: '&',
				},
				controller: AssessmentQuestionController,
				controllerAs: 'questionCtl',
				link: function (scope, element, attrs, controller) {
				},
			};
		});

})(angular, bleedHd);
