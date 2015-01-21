
(function (angular) {

	angular.module('question').config(function (QuestionTypeRegistryProvider) {
		QuestionTypeRegistryProvider.registerTypeWithName('textarea', 'base', function (parent) {
			return {
				ctor: function TextareaQuestion(scope, definition) {
					parent(this)(scope, definition);

					this.rows = definition.rows || 10;
					this.cols = definition.cols || 80;
				},
				members: {
					reset: function (event, data) {
						// keep the this.data.supplements binding intact by only resetting the value
						this.data.value = null;
					},
				},
			};
		});
	});

})(angular);
