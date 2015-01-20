
(function (angular) {

	angular.module('question').config(function (QuestionTypeRegistryProvider, RegExpressionsProvider) {
		QuestionTypeRegistryProvider.registerTypeWithName('text', 'base', function (parent) {
			return {
				ctor: function TextQuestion(scope, definition) {
					parent(this)(scope, definition);

					this.filterExpression = RegExpressionsProvider.$get().parse(definition.pattern);
				},
				members: {
				},
			};
		});
	});

})(angular);
