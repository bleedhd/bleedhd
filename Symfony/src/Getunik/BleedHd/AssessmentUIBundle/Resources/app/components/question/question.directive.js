
(function (angular, bleedHd) {

	function AssessmentQuestionController($scope) {
		this.question = $scope.question;
		this.result = this.question.result || { value: null, meta: 'nya' };

		var that = this;

		$scope.$on('result-changed', function (event, result) {
			that.result.meta = null;
			$scope.$emit('response-changed', {
				slug: that.question.slug,
				result: that.result,
			});
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
					question: '=data',
				},
				controller: AssessmentQuestionController,
				controllerAs: 'questionCtl',
				link: function (scope, element, attrs, controller) {
				},
			};
		});

})(angular, bleedHd);
