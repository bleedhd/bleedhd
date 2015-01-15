
(function (angular, bleedHd) {

	function BaseQuestion() {
	}

	angular.extend(BaseQuestion.prototype, {
		construct: function (scope, question) {
			this.scope = scope;
			this.question = question;
			this.slug = this.question.slug;

			this.data = angular.copy(scope.data()) || this.emptyData();
			// the fun of translating objects to JSON, to PHP array, to JSON (in the DB), back to PHP array,
			// back to JSON, back to objects... {} => JSON {} => PHP array() => JSON [] => PHP array() => JSON [] => []
			if (angular.isArray(this.data.supplements) && this.data.supplements.length === 0) {
				this.data.supplements = {};
			}

			var that = this;
			this.scope.$on('q-do-reset', function (event, data) {
				that.reset(event, data);
			});
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
		},
	});


	function YesNoQuestion(scope, question) {
		this.construct(scope, question);

		this.options = $.extend({
			yes: { label: 'Yes', value: true },
			no: { label: 'No', value: false },
		}, this.question.options);
	}

	angular.extend(YesNoQuestion.prototype, BaseQuestion.prototype, {
		link: function (element) {
			var that = this;

			this.scope.$watch('questionCtl.data.value', function (newValue, oldValue) {
				if (newValue !== oldValue && newValue !== null) {
					that.scope.$emit('q-data-changed', that.data);
				}
			});
		},
	});


	var questionTypes = {
		yesno: YesNoQuestion,
	};


	//////

	angular.module('question')
		.directive('question', function ($templateRequest, $compile) {
			return {
				restrict: 'E',
				scope: {
					question: '&',
					data: '&',
				},
				compile: function (element, attrs, transclude) {
					// return simple linking function that dynamically loads the question template
					return function (scope, element, attrs) {

						var question = scope.question();
						scope.questionCtl = new questionTypes[question.type](scope, question);

						$templateRequest(bleedHd.getView('question', 'question-type-' + question.type)).then(function (template) {
							element.append($compile(template)(scope));
							scope.questionCtl.link(element);
						});
					};
				},
			};
		});

})(angular, bleedHd);
