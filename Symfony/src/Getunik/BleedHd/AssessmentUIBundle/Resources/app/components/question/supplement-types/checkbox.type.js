
(function (angular) {

	angular.module('question').config(function (SupplementTypeRegistryProvider) {
		SupplementTypeRegistryProvider.registerTypeWithName('checkbox', 'base', function (parent) {
			return {
				ctor: function CheckboxSupplement (scope, definition) {
					parent(this)(scope, definition);
					// make sure single checkbox supplements always have a value
					this.supplement[this.definition.slug] = this.supplement[this.definition.slug] || false;
				},
				members: {
				},
			};
		});
	});

})(angular);
