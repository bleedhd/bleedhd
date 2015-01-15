
(function (angular, bleedHd) {

	function YesNoQuestion(scope, question) {
		this.construct(scope, question);

		this.options = $.extend({
			yes: { label: 'Yes', value: true },
			no: { label: 'No', value: false },
		}, this.question.options);
	}

	angular.module('question').run(function (TypeRegistry) {
		TypeRegistry.registerQuestionType('yesno', YesNoQuestion, {
			link: function (element) {
				var that = this;

				this.scope.$watch('questionCtl.data.value', function (newValue, oldValue) {
					if (newValue !== oldValue && newValue !== null) {
						that.scope.$emit('q-data-changed', that.data);
					}
				});
			},
		});
	});

})(angular, bleedHd);
