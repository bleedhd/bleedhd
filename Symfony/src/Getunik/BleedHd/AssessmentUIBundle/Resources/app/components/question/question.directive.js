
(function (angular, bleedHd) {

	function AssessmentQuestionController() {
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
