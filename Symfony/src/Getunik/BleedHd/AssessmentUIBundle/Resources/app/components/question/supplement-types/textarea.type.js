
(function (angular) {

	angular.module('question').config(function (SupplementTypeRegistryProvider, RegExpressionsProvider) {
		SupplementTypeRegistryProvider.registerTypeWithName('textarea', 'base', function (parent) {
			return {
				ctor: function TextareaSupplement (scope, definition) {
					parent(this)(scope, definition);

					this.rows = this.definition.rows || 5;
					this.cols = this.definition.cols || 80;
				},
				members: {
					getDefault: function () {
						return '';
					},
				},
			};
		});
	});

})(angular);
