
(function (angular) {

	angular.module('question').config(function (SupplementTypeRegistryProvider) {
		SupplementTypeRegistryProvider.registerTypeWithName('base', null, function (parent) {
			return {
				ctor: function BaseSupplement (scope, definition) {
					this.scope = scope;
					this.definition = definition;
					this.slug = this.definition.slug;

					this.supplementObject = scope.data() || {};
					// initialize supplement with its default value
					this.value(this.value() || this.getDefault());
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
					getDefault: function () {
						return null;
					},
					/**
					 * AngularJS style getter/setter property for nice and convenient data binding "into"
					 * the supplementObject
					 */
					value: function (newValue) {
						if (newValue !== undefined) {
							this.supplementObject[this.slug] = newValue;
						}

						return this.supplementObject[this.slug];
					},
				},
			};
		});
	});

})(angular);
