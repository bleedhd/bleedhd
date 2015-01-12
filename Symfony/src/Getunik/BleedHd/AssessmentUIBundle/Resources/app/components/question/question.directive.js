
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
					data: '=',
				},
				controller: AssessmentQuestionController,
				link: function (scope, element, attrs, controller) {
				},
			};
		});

})(angular, bleedHd);
