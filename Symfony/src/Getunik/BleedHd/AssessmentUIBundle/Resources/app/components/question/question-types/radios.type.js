
(function (angular) {

	angular.module('question').config(function (QuestionTypeRegistryProvider) {
		QuestionTypeRegistryProvider.registerTypeWithName('radios', 'base', function (parent) {
			return {
				ctor: function RadiosQuestion(scope, definition) {
					parent(this)(scope, definition);

					var that = this;

					this.options = $.map(this.definition.options, function (option) {
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
						parent(this, "onChange")();
					},
					getOptions: function () {
						return this.options;
					},
				},
			};
		});
	});

})(angular);
