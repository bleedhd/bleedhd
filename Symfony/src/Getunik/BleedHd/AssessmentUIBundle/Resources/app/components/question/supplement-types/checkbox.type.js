
(function (angular) {

	angular.module('question').config(function (SupplementTypeRegistryProvider) {
		SupplementTypeRegistryProvider.registerTypeWithName('checkbox', 'base', function (parent) {
			return {
				ctor: function CheckboxSupplement (scope, definition) {
					parent(this)(scope, definition);

					this.trueValue = angular.isUndefined(definition.true_value) ? true : definition.true_value;
					this.falseValue = angular.isUndefined(definition.false_value) ? false : definition.false_value;
				},
				members: {
					getDefault: function () {
						return this.falseValue;
					},
				},
			};
		});
	});

})(angular);
