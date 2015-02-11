
(function (angular) {

	angular.module('question').config(function (SupplementTypeRegistryProvider) {
		SupplementTypeRegistryProvider.registerTypeWithName('checkbox', 'base', function (parent) {
			return {
				ctor: function CheckboxSupplement (scope, definition) {
					this.options = $.extend({
						yes: { label: 'Yes', value: true },
						no: { label: 'No', value: false },
					}, definition.options);

					parent(this)(scope, definition);
				},
				members: {
					getDefault: function () {
						return this.options.no.value;
					},
				},
			};
		});
	});

})(angular);
