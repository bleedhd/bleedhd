
(function (angular) {

	angular.module('question').config(function (SupplementTypeRegistryProvider, RegExpressionsProvider) {
		SupplementTypeRegistryProvider.registerTypeWithName('text', 'base', function (parent) {
			return {
				ctor: function TextSupplement (scope, definition) {
					parent(this)(scope, definition);
					// make sure text supplements always have a value ('' as default)
					this.supplement[this.definition.slug] = this.supplement[this.definition.slug] || '';
					this.filterExpression = RegExpressionsProvider.$get().parse(definition.pattern);
				},
				members: {
					onChange: function () {
						this.scope.$emit('q-supplement-changed', this);
					},
				},
			};
		});
	});

})(angular);
