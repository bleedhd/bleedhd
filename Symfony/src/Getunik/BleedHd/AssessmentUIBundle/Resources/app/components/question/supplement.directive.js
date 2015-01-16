
(function (angular, bleedHd) {

	angular.module('question')
		.directive('supplement', function (QuestionTypeRegistry) {
			return {
				restrict: 'E',
				scope: {
					active: '=',
					definition: '&',
					data: '&',
				},
				compile: function (element, attrs, transclude) {
					// return simple linking function that dynamically loads the question template
					return function (scope, element, attrs) {
						var definition = scope.definition();
						scope.supplementCtl = QuestionTypeRegistry.getSupplementType(definition.type, definition.variant).instantiate(scope, element, definition);
					};
				},
			};
		});

})(angular, bleedHd);
