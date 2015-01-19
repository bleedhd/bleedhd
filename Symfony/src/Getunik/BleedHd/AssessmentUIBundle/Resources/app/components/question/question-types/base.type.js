
(function (angular) {

	angular.module('question').config(function (QuestionTypeRegistryProvider) {
		QuestionTypeRegistryProvider.registerTypeWithName('base', null, function (parent) {
			return {
				ctor: function BaseQuestion (scope, question) {
					this.scope = scope;
					this.question = question;
					this.slug = this.question.slug;

					this.data = angular.copy(scope.data()) || this.emptyData();
					this.data.supplements = this.normalizeSupplement(this.data.supplements);
				},
				members: {
					getTemplateHierarchy: function () {
						return [
							this.question.type + '-' + this.question.variant,
							this.question.type,
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
					linkWithElement: function (element) {
						this.element = element;
						this.baseLink(element);
						this.link(element);
					},
					baseLink: function (element) {
						var that = this;
						this.scope.$on('q-do-reset', function (event, data) {
							that.reset(event, data);
						});

						this.scope.$on('q-supplement-changed', function (event, data) {
							that.scope.$emit('q-data-changed', that);
						});
					},
					link: function (element) {
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
