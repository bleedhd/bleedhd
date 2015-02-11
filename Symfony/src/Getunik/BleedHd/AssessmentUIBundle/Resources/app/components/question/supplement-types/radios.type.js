
(function (angular) {

	angular.module('question').config(function (SupplementTypeRegistryProvider) {
		SupplementTypeRegistryProvider.registerTypeWithName('radios', 'base', ['option'], function (parent) {
			return {
				ctor: function RadiosSupplement (scope, definition) {
					parent(this)(scope, definition);

					this.options = this.processOptions(this.definition.options);
				},
				members: {
				},
			};
		});
	});

})(angular);
