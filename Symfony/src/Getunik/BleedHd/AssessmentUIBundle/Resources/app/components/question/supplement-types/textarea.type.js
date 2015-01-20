
(function (angular) {

	angular.module('question').config(function (SupplementTypeRegistryProvider, RegExpressionsProvider) {
		SupplementTypeRegistryProvider.registerTypeWithName('textarea', 'base', function (parent) {
			return {
				ctor: function TextareaSupplement (scope, definition) {
					parent(this)(scope, definition);
					// make sure text supplements always have a value ('' as default)
					this.supplement[this.definition.slug] = this.supplement[this.definition.slug] || '';
					this.rows = this.definition.rows || 5;
					this.cols = this.definition.cols || 80;
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
