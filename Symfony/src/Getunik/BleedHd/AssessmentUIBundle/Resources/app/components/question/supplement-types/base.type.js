
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
					/**
					 * @returns {Array} - an array of potential fallback template names to load with this supplement type
					 */
					getTemplateHierarchy: function () {
						return [
							this.definition.type + '-' + this.definition.variant,
							this.definition.type,
						];
					},
					/**
					 * Toggles the 'disabled' class on the directive's root element depending on the given active state
					 *
					 * @param {bool} active - true, if the supplement should be active, false otherwise
					 */
					setActive: function (active) {
						this.element.toggleClass('disabled', !active);
					},
					/**
					 * Performs AngularJS style 'linking' of the supplement's compiled template with the
					 * supplement controller (which is the current supplement type instance)
					 *
					 * @param {jQueryObj} element - the supplement directive's root element which contains all the compiled templates at this stage
					 */
					link: function (element) {
						var that = this;

						that.element = element;

						that.element
							.addClass('supplement')
							.addClass(that.definition.style)
							.addClass('variant-' + (this.definition.variant || 'default'));

						that.scope.$watch('active', function (newValue) {
							that.setActive(newValue);
						});

						that.setActive(that.scope.active);
					},
					/**
					 * ng-change event callback that is called whenever the result of this supplement changes.
					 * This simply triggers the 'q-supplement-changed' event to notify its parent container.
					 */
					onChange: function () {
						this.scope.$emit('q-supplement-changed', this);
					},
					/**
					 * Gets the default / initial value for this supplement type
					 *
					 * @returns {any} - depends on the supplement, the default assumption is null
					 */
					getDefault: function () {
						return null || this.definition.default;
					},
					/**
					 * AngularJS style getter/setter property for nice and convenient data binding "into"
					 * the supplementObject
					 *
					 * @param {any} newValue - the new value when the 'property' is used as a setter
					 * @returns {any} - the current value of the 'property'
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
