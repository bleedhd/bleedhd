
(function (angular) {

	angular.module('question').config(function (QuestionTypeRegistryProvider) {
		QuestionTypeRegistryProvider.registerTypeWithName('yesno', 'base', function (parent) {
			return {
				ctor: function YesNoQuestion(scope, definition) {
					parent(this)(scope, definition);

					this.options = $.extend({
						yes: { label: 'Yes', value: true },
						no: { label: 'No', value: false },
					}, this.definition.options);

					var that = this;
					angular.forEach(this.getOptions(), function (option) {
						option.supplementData = option.value === that.data.value ? that.data.supplements : {};
					});
				},
				members: {
					onChange: function (option) {
						this.data.supplements = option.supplementData;
						parent(this, "onChange")();
					},
					getOptions: function () {
						return [this.options.yes, this.options.no];
					},
				},
			};
		});
	});

})(angular);
