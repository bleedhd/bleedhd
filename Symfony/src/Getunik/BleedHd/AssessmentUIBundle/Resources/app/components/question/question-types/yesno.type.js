
(function (angular) {

	angular.module('question').config(function (QuestionTypeRegistryProvider) {
		QuestionTypeRegistryProvider.registerTypeWithName('yesno', 'base', function (parent) {
			return {
				ctor: function YesNoQuestion(scope, question) {
					parent(this)(scope, question);

					this.options = $.extend({
						yes: { label: 'Yes', value: true },
						no: { label: 'No', value: false },
					}, this.question.options);

					var that = this;
					angular.forEach(this.getOptions(), function (option) {
						option.supplementData = option.value === that.data.value ? that.data.supplements : {};
					});
				},
				members: {
					onChange: function (option) {
						this.data.supplements = option.supplementData;
						this.scope.$emit('q-data-changed', this);
					},
					getOptions: function () {
						return [this.options.yes, this.options.no];
					},
				},
			};
		});
	});

})(angular);
