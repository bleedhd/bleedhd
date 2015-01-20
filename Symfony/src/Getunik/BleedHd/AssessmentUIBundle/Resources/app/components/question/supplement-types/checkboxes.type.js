
(function (angular) {

	angular.module('question').config(function (SupplementTypeRegistryProvider) {
		SupplementTypeRegistryProvider.registerTypeWithName('checkboxes', 'base', function (parent) {
			return {
				ctor: function CheckboxesSupplement (scope, definition) {
					parent(this)(scope, definition);

					var that = this,
						values = {};

					// key values by 'value' so that we can link them to the supplement definitions
					angular.forEach(this.value(), function (item) {
						values[item] = true;
					});

					this.options = $.map(this.definition.options, function (option) {
						return angular.extend({}, option, {
							binding: {
								value: values[option.value] === true ? option.value : null,
							},
						});
					});
				},
				members: {
					getDefault: function () {
						return [];
					},
					getOptions: function () {
						return this.options;
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
