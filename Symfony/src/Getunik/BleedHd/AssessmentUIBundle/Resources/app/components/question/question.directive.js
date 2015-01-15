
(function (angular, bleedHd) {

	angular.module('question')
		.directive('question', function (TypeRegistry) {
			return {
				restrict: 'E',
				scope: {
					question: '&',
					data: '&',
				},
				compile: function (element, attrs, transclude) {
					// return simple linking function that dynamically loads the question template
					return function (scope, element, attrs) {
						var question = scope.question();
						scope.questionCtl = TypeRegistry.getQuestionType(question.type, question.variant).instantiate(scope, element, question);
					};
				},
			};
		});

})(angular, bleedHd);
