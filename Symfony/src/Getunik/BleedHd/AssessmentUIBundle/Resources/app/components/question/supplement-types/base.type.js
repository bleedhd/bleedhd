
(function (angular) {

	angular.module('question').config(function (SupplementTypeRegistryProvider) {
		SupplementTypeRegistryProvider.registerTypeWithName('base', null, function (parent) {
			return {
				ctor: function BaseSupplement (scope, definition) {
					this.scope = scope;
					this.definition = definition;
					this.supplement = scope.data() || {};
				},
				members: {
					getTemplateHierarchy: function () {
						return [
							this.definition.type + '-' + this.definition.variant,
							this.definition.type,
						];
					},
					deactivate: function () {
						this.element.addClass('disabled');
					},
					setActive: function (active) {
						this.element.toggleClass('disabled', !active);
					},
					link: function (element) {
						var that = this;

						that.element = element;

						that.scope.$watch('active', function (newValue) {
							that.setActive(newValue);
						});

						that.setActive(that.scope.active);
					},
					onChange: function () {
						this.scope.$emit('q-supplement-changed', this);
					},
				},
			};
		});
	});

})(angular);
