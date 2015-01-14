
(function (angular, bleedHd) {

	function AssessmentQuestionContainerController($scope) {
		this.question = $scope.question();
		this.response = $scope.response();
		this.result = this.response.result;

		var that = this;

		$scope.$on('q-data-changed', function (event, data) {
			that.result.meta = null;
			that.result.data = data;
			$scope.$emit('response-changed', that.response);
		});

		$scope.$watch('containerCtl.result.meta', function (newValue, oldValue) {
			if (newValue !== oldValue && newValue !== null) {
				that.result.data = null;
				$scope.$emit('response-changed', that.response);
			}
		});
	}

	angular.extend(AssessmentQuestionContainerController.prototype, {
	});


	angular.module('question')
		.directive('container', function () {
			return {
				templateUrl: bleedHd.getView('question', 'container'),
				restrict: 'E',
				scope: {
					question: '&data',
					response: '&',
				},
				controller: AssessmentQuestionContainerController,
				controllerAs: 'containerCtl',
				link: function (scope, element, attrs, controller) {
				},
			};
		});

})(angular, bleedHd);
