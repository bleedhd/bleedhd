
(function (angular) {

	angular.module('question').config(function (QuestionTypeRegistryProvider) {
		QuestionTypeRegistryProvider.registerTypeWithName('radios', 'base', function (parent) {
			return {
				ctor: function RadiosQuestion(scope, question) {
					parent(this)(scope, question);

					var that = this;

					this.options = $.map(this.question.options, function (option) {
						var supplements = option.value === that.data.value ? that.normalizeSupplement(that.data.supplements) : {};
						return angular.extend({}, option, {
							binding: {
								supplements: supplements,
							},
						});
					});
				},
				members: {
					onChange: function (option) {
						this.data.supplements = option.binding.supplements;
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
