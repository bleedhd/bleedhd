
(function (angular) {

	angular.module('question').config(function (QuestionTypeRegistryProvider) {
		QuestionTypeRegistryProvider.registerTypeWithName('textarea', 'base', function (parent) {
			return {
				ctor: function TextareaQuestion(scope, question) {
					parent(this)(scope, question);

					this.rows = question.rows || 10;
					this.cols = question.cols || 80;
				},
				members: {
				},
			};
		});
	});

})(angular);
