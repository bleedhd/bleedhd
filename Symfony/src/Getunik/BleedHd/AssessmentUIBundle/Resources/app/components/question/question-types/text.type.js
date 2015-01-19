
(function (angular) {

	angular.module('question').config(function (QuestionTypeRegistryProvider) {
		QuestionTypeRegistryProvider.registerTypeWithName('text', 'base', function (parent) {
			return {
				ctor: function TextQuestion(scope, question) {
					parent(this)(scope, question);
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
