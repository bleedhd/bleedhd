
(function (angular, bleedHd) {

	function CheckboxSupplement(scope, definition) {
		this.construct(scope, definition);
		scope.value = this.supplement[this.definition.slug];
	}

	angular.module('question').run(function (QuestionTypeRegistry) {
		QuestionTypeRegistry.registerSupplementType('checkbox', CheckboxSupplement, {
			link: function (element) {
			},
			onChange: function () {
				this.scope.$emit('q-supplement-changed', this);
			},
		});
	});

})(angular, bleedHd);
