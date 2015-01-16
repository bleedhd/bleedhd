
(function (angular) {

	angular.module('question').config(function (SupplementTypeRegistryProvider) {
		SupplementTypeRegistryProvider.registerTypeWithName('base', null, function (parent) {
			return {
				ctor: function BaseSupplement (scope, definition) {
					this.scope = scope;
					this.definition = definition;
					this.supplement = scope.data();
				},
				members: {
					getTemplateHierarchy: function () {
						return [
							this.definition.type + '-' + this.definition.variant,
							this.definition.type,
						];
					},
					linkWithElement: function (element) {
						this.element = element;
						this.baseLink(element);
						this.link(element);
					},
					deactivate: function () {
						this.element.addClass('disabled');
					},
					setActive: function (active) {
						this.element.toggleClass('disabled', !active);
					},
					baseLink: function (element) {
						var that = this;

						that.scope.$watch('active', function (newValue) {
							that.setActive(newValue);
						});

						that.setActive(that.scope.active);
					},
					link: function (element) {
					},
				},
			};
		});
	});

})(angular);
