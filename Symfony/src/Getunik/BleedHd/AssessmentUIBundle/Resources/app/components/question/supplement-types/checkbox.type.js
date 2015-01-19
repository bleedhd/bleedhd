

(function (angular) {

	angular.module('question').config(function (SupplementTypeRegistryProvider) {
		SupplementTypeRegistryProvider.registerTypeWithName('checkbox', 'base', function (parent) {
			return {
				ctor: function CheckboxSupplement (scope, definition) {
					parent(this)(scope, definition);
					scope.value = this.supplement[this.definition.slug];
				},
				members: {
					link: function (element) {
					},
					onChange: function () {
						this.scope.$emit('q-supplement-changed', this);
					},
				},
			};
		});
	});

})(angular);
