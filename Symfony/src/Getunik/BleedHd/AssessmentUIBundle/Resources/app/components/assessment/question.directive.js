
(function (angular, bleedHd) {

	function AssessmentQuestionController() {
	}

	angular.extend(AssessmentQuestionController.prototype, {

	});


	angular.module('assessment')
		.directive('question', function () {
			return {
				template: function (element, attrs) {
					return '<div>QUESTION</div>';
				},
				restrict: 'E',
				scope: {
					data: '=',
				},
				controller: AssessmentQuestionController,
				link: function (scope, element, attrs, controller) {
				},
			};
		});

})(angular, bleedHd);
