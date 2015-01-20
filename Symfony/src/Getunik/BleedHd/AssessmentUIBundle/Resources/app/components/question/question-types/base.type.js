
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
					getTemplateHierarchy: function () {
						return [
							this.definition.type + '-' + this.definition.variant,
							this.definition.type,
						];
					},
					emptyData: function () {
						return {
							value: null,
							supplements: {},
						};
					},
					reset: function (event, data) {
						this.data = this.emptyData();
					},
					link: function (element) {
						var that = this;

						that.element = element;

						this.scope.$on('q-do-reset', function (event, data) {
							that.reset(event, data);
						});

						this.scope.$on('q-supplement-changed', function (event, data) {
							that.scope.$emit('q-data-changed', that);
						});
					},
					onChange: function () {
						this.scope.$emit('q-data-changed', this);
					},
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
