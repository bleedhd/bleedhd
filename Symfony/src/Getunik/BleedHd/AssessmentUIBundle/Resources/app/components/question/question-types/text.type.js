
(function (angular) {

	angular.module('question').config(function (QuestionTypeRegistryProvider, RegExpressionsProvider) {
		QuestionTypeRegistryProvider.registerTypeWithName('text', 'base', function (parent) {
			return {
				ctor: function TextQuestion(scope, question) {
					parent(this)(scope, question);

					this.filterExpression = RegExpressionsProvider.$get().parse(question.pattern);
				},
				members: {
				},
			};
		});
	});

})(angular);
