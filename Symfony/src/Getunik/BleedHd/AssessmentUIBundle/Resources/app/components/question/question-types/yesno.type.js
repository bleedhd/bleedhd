
(function (angular, bleedHd) {

	function YesNoQuestion(scope, question) {
		this.construct(scope, question);

		this.options = $.extend({
			yes: { label: 'Yes', value: true },
			no: { label: 'No', value: false },
		}, this.question.options);

		var that = this;
		angular.forEach(this.getOptions(), function (option) {
			option.supplementData = option.value === that.data.value ? that.data.supplements : {};
		});
	}

	angular.module('question').run(function (TypeRegistry) {
		TypeRegistry.registerQuestionType('yesno', YesNoQuestion, {
			link: function (element) {
			},
			onChange: function (option) {
				this.data.supplements = option.supplementData;
				this.scope.$emit('q-data-changed', this);
			},
			getOptions: function () {
				return [this.options.yes, this.options.no];
			},
		});
	});

})(angular, bleedHd);
