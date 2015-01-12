
(function (angular, bleedHd) {

	function AssessmentQuestionController($scope) {
		this.question = $scope.question;
		this.result = this.question.result || { value: null, meta: 'nya' };

		var that = this;

		$scope.$on('result-changed', function (event, result) {
			$scope.$emit('response-changed', {
				slug: that.question.slug,
				result: that.result,
			});
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
