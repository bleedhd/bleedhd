
(function (angular) {

	angular.module('question').config(function (SupplementTypeRegistryProvider) {
		SupplementTypeRegistryProvider.registerTypeWithName('checkboxes', 'base', ['option'], function (parent) {
			return {
				ctor: function CheckboxesSupplement (scope, definition) {
					parent(this)(scope, definition);

					var that = this,
						values = {};

					// key values by 'value' so that we can link them to the supplement definitions
					angular.forEach(that.value(), function (item) {
						values[item] = true;
					});

					that.options = that.processOptions(that.definition.options, function (option) {
						return {
							value: values[option.value] === true ? option.value : null,
						};
					});
				},
				members: {
					getDefault: function () {
						return angular.isArray(this.definition.default) ? this.definition.default : [];
					},
					updateData: function () {
						this.value($.map(this.getOptions(), function (option) {
							return option.binding.value;
						}));

						return this.value().length > 0;
					},
					onChange: function () {
						this.updateData();
						parent(this, "onChange")();
					},
				},
			};
		});
	});

})(angular);
