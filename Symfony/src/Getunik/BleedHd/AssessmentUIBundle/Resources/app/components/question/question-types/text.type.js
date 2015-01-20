
(function (angular) {

	var regexExp = /^\/(.*)\/(.*)$/,
		namedExpressions = {
			integer: /^\d*$/,
			decimal: /^\d*(\.\d*)?$/,
		};

	angular.module('question').config(function (QuestionTypeRegistryProvider) {
		QuestionTypeRegistryProvider.registerTypeWithName('text', 'base', function (parent) {
			return {
				ctor: function TextQuestion(scope, question) {
					parent(this)(scope, question);

					this.filterExpression = null;
					if (question.pattern !== undefined) {
						var match = question.pattern.match(regexExp);
						if (match !== null) {
							this.filterExpression = new RegExp(match[1], match[2]);
						} else {
							this.filterExpression = namedExpressions[question.pattern];
						}
					}
				},
				members: {
					link: function (element) {
					},
					onChange: function (option) {
						this.scope.$emit('q-data-changed', this);
					},
				},
			};
		});
	});

})(angular);
