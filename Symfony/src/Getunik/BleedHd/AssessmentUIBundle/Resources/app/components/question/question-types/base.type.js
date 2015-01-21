
(function (angular) {

	angular.module('question').config(function (QuestionTypeRegistryProvider) {
		QuestionTypeRegistryProvider.registerTypeWithName('base', null, function (parent) {
			return {
				ctor: function BaseQuestion (scope, definition) {
					this.scope = scope;
					this.definition = definition;
					this.slug = this.definition.slug;

					this.data = angular.copy(scope.data()) || this.emptyData();
					this.data.supplements = this.normalizeSupplement(this.data.supplements);
				},
				members: {
					/**
					 * @returns {Array} - an array of potential fallback template names to load with this question type
					 */
					getTemplateHierarchy: function () {
						return [
							this.definition.type + '-' + this.definition.variant,
							this.definition.type,
						];
					},
					/**
					 * @returns {Object} - this question type's null data object with a 'value' and a 'supplements' property
					 */
					emptyData: function () {
						return {
							value: null,
							supplements: {},
						};
					},
					/**
					 * Resets the question to its default empty state
					 *
					 * @param {Object} event - the AngularJS event that triggered the reset
					 * @param {Object} data - the AngularJS event data from the triggering event
					 */
					reset: function (event, data) {
						this.data = this.emptyData();
					},
					/**
					 * Performs AngularJS style 'linking' of the question's compiled template with the
					 * question controller (which is the current question type instance)
					 *
					 * @param {jQueryObj} element - the question directive's root element which contains all the compiled templates at this stage
					 */
					link: function (element) {
						var that = this;

						that.element = element;

						that.element
							.addClass('question')
							.addClass(that.definition.style)
							.addClass('variant-' + (this.definition.variant || 'default'));

						that.scope.$on('q-do-reset', function (event, data) {
							that.reset(event, data);
						});

						that.scope.$on('q-supplement-changed', function (event, data) {
							that.scope.$emit('q-data-changed', that);
						});
					},
					/**
					 * ng-change event callback that is called whenever the result of this question changes.
					 * This simply triggers the 'q-data-changed' event to notify its parent container.
					 */
					onChange: function () {
						this.scope.$emit('q-data-changed', this);
					},
					/**
					 * Due to a funny JavaScript / PHP semantic gap, the supplements 'objects' that are retrieved
					 * from the server actually arrive as arrays if the object was empty when it was saved...
					 * This function normalizes the supplements back into their intended form.
					 *
					 * @param {Object|Array} supplements - the supplements object that should be normalized
					 * @returns {Object} the given supplements object if it was already an object, otherwise it returns an empty object
					 */
					normalizeSupplement: function (supplements) {
						// the fun of translating objects to JSON, to PHP array, to JSON (in the DB), back to PHP array,
						// back to JSON, back to objects... {} => JSON {} => PHP array() => JSON [] => PHP array() => JSON [] => []
						if (supplements === null || supplements === undefined || (angular.isArray(supplements) && supplements.length === 0)) {
							return {};
						} else {
							return supplements;
						}
					},
				},
			};
		});
	});

})(angular);
