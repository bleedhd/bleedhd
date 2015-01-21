
(function (angular) {

	angular.module('question').config(function (QuestionTypeRegistryProvider) {
		QuestionTypeRegistryProvider.registerTypeWithName('yesno', 'base', ['option'], function (parent) {
			return {
				ctor: function YesNoQuestion(scope, definition) {
					parent(this)(scope, definition);

					var that = this,
						optionsDef = $.extend({
						yes: { label: 'Yes', value: true },
						no: { label: 'No', value: false },
					}, this.definition.options);

					that.options = that.processOptions([optionsDef.yes, optionsDef.no], function (option) {
						return {
							supplements: option.value === that.data.value ? that.data.supplements : {},
						};
					});
				},
				members: {
					onChange: function (option) {
						this.data.supplements = option.binding.supplements;
						parent(this, "onChange")();
					},
				},
			};
		});
	});

})(angular);
