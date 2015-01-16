
(function (angular, bleedHd) {

	angular.module('question')
		.directive('supplement', function (SupplementTypeRegistry, TemplateTypeService) {
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

						scope.supplementCtl = TemplateTypeService.instantiate(SupplementTypeRegistry, scope, element, definition);
					};
				},
			};
		});

})(angular, bleedHd);
