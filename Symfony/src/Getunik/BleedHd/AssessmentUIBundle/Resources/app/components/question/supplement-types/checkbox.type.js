
(function (angular) {

	angular.module('question').config(function (SupplementTypeRegistryProvider) {
		SupplementTypeRegistryProvider.registerTypeWithName('checkbox', 'base', function (parent) {
			return {
				ctor: function CheckboxSupplement (scope, definition) {
					parent(this)(scope, definition);
				},
				members: {
					getDefault: function () {
						return false;
					},
				},
			};
		});
	});

})(angular);
