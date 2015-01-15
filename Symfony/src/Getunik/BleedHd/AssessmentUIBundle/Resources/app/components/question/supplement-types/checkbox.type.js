
(function (angular, bleedHd) {

	function CheckboxSupplement(scope, definition) {
		this.construct(scope, definition);
	}

	angular.module('question').run(function (TypeRegistry) {
		TypeRegistry.registerSupplementType('checkbox', CheckboxSupplement, {
			link: function (element) {
				var that = this;

				this.scope.$watch('value', function (newValue, oldValue) {
					if (newValue !== oldValue && newValue !== null) {
						that.supplement[that.definition.slug] = newValue;
						that.scope.$emit('q-supplement-changed', that);
					}
				});
			},
		});
	});

})(angular, bleedHd);
