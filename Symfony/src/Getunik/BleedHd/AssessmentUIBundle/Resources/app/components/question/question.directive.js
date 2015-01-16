
(function (angular, bleedHd) {

	angular.module('question')
		.directive('question', function (MyQuestionTypeRegistry, TemplateTypeService) {
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

						scope.questionCtl = TemplateTypeService.instantiate(MyQuestionTypeRegistry, scope, element, question);
					};
				},
			};
		});

})(angular, bleedHd);
