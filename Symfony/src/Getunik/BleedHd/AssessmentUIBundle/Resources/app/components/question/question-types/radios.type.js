
(function (angular) {

	angular.module('question').config(function (QuestionTypeRegistryProvider) {
		QuestionTypeRegistryProvider.registerTypeWithName('radios', 'baseoption', function (parent) {
			return {
				ctor: function RadiosQuestion(scope, definition) {
					parent(this)(scope, definition);

					var that = this;

					that.options = that.processOptions(that.definition.options, function (option) {
						return {
							supplements: (option.value === that.data.value ? that.normalizeSupplement(that.data.supplements) : {}),
						};
					});
				},
				members: {
					onChange: function (option) {
						this.data.supplements = option.binding.supplements;
						parent(this, "onChange")();
					},
				},
			};
		});
	});

})(angular);
