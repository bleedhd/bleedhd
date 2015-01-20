
(function (angular) {

	/**
	 * The implementation will make sure that the elements in the data array of the result have
	 * the same order as the options specified on the question, but it will not assume that the
	 * initial data object has that order.
	 */
	angular.module('question').config(function (QuestionTypeRegistryProvider) {
		QuestionTypeRegistryProvider.registerTypeWithName('checkboxes', 'base', function (parent) {
			return {
				ctor: function CheckboxesQuestion(scope, question) {
					parent(this)(scope, question);

					var that = this,
						values = {};

					// binding endpoint for the reset option (radio)
					this.resetCheckboxes = false;

					// key values by 'value' so that we can link them to the supplement definitions
					angular.forEach(this.data, function (item) {
						values[item.value] = item;
					});

					this.options = $.map(this.question.options, function (option) {
						var dataItem = values[option.value] || {};
						return angular.extend({}, option, {
							binding: {
								value: dataItem.value || null,
								supplements: that.normalizeSupplement(dataItem.supplements),
							},
						});
					});
				},
				members: {
					emptyData: function () {
						return [];
					},
					getOptions: function () {
						return this.options;
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
						this.scope.$emit('q-data-changed', this);
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
