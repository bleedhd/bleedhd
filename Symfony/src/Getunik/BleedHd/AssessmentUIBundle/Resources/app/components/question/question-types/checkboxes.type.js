
(function (angular) {

	/**
	 * The implementation will make sure that the elements in the data array of the result have
	 * the same order as the options specified on the question, but it will not assume that the
	 * initial data object has that order.
	 */
	angular.module('question').config(function (QuestionTypeRegistryProvider) {
		QuestionTypeRegistryProvider.registerTypeWithName('checkboxes', 'baseoption', function (parent) {
			return {
				ctor: function CheckboxesQuestion(scope, definition) {
					parent(this)(scope, definition);

					var that = this,
						values = {};

					// binding endpoint for the reset option (radio)
					that.resetCheckboxes = false;

					// key values by 'value' so that we can link them to the supplement definitions
					angular.forEach(that.data, function (item) {
						values[item.value] = item;
					});

					that.options = that.processOptions(that.definition.options, function (option) {
						var dataItem = values[option.value] || {};

						return {
							value: dataItem.value || null,
							supplements: that.normalizeSupplement(dataItem.supplements),
						};
					});
				},
				members: {
					emptyData: function () {
						return [];
					},
					reset: function (event, data) {
						this.resetCheckboxes = false;
						this.uncheckAll();
					},
					uncheckAll: function () {
						angular.forEach(this.options, function (option) {
							option.binding.value = null;
						});
						this.updateData();
					},
					updateData: function () {
						this.data = $.map(this.getOptions(), function (option) {
							return option.binding.value === null ? null : option.binding;
						});
						return this.data.length > 0;
					},
					onChange: function (option) {
						this.resetCheckboxes = !this.updateData();
						parent(this, "onChange")();
					},
					onResetCheckboxes: function () {
						this.uncheckAll();
						this.scope.$emit('q-data-changed', this);
					},
				},
			};
		});
	});

})(angular);
