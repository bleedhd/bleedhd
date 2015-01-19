
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

					// key values by 'value' so that we can link them to the supplement definitions
					this.data.forEach(function (item) {
						values[item.value] = item;
					});

					this.options = this.question.options.map(function (option) {
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
					link: function (element) {
					},
					emptyData: function () {
						return [];
					},
					onChange: function (option) {
						this.data = $.map(this.getOptions(), function (option) {
							return option.binding.value === null ? null : option.binding;
						});
						this.scope.$emit('q-data-changed', this);
					},
					getOptions: function () {
						return this.options;
					},
				},
			};
		});
	});

})(angular);
