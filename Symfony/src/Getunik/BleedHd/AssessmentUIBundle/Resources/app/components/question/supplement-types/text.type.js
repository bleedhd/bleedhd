
(function (angular) {

	angular.module('question').config(function (SupplementTypeRegistryProvider, RegExpressionsProvider) {
		SupplementTypeRegistryProvider.registerTypeWithName('text', 'base', function (parent) {
			return {
				ctor: function TextSupplement (scope, definition) {
					parent(this)(scope, definition);

					this.filterExpression = RegExpressionsProvider.$get().parse(definition.pattern);
				},
				members: {
					getDefault: function () {
						return '' || this.definition.default;
					},
				},
			};
		});
	});

})(angular);
