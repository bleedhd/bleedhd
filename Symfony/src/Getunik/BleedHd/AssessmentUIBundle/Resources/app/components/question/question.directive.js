
(function (angular, bleedHd) {

	angular.module('question')
		.directive('question', function (QuestionTypeRegistry, TemplateTypeService) {
			return {
				restrict: 'E',
				scope: {
					definition: '&',
					data: '&',
				},
				compile: function (element, attrs, transclude) {
					// return simple linking function that dynamically loads the question template
					return function (scope, element, attrs) {
						var definition = scope.definition();

						scope.questionCtl = TemplateTypeService.instantiate(QuestionTypeRegistry, scope, element, definition);
					};
				},
			};
		});

})(angular, bleedHd);
