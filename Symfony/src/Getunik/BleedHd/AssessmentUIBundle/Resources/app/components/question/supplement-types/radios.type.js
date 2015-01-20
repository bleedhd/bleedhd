
(function (angular) {

	angular.module('question').config(function (SupplementTypeRegistryProvider) {
		SupplementTypeRegistryProvider.registerTypeWithName('radios', 'base', function (parent) {
			return {
				ctor: function RadiosSupplement (scope, definition) {
					parent(this)(scope, definition);

					this.options = $.map(this.definition.options, function (option) {
						return angular.extend({}, option);
					});
				},
				members: {
					getOptions: function () {
						return this.options;
					},
				},
			};
		});
	});

})(angular);
