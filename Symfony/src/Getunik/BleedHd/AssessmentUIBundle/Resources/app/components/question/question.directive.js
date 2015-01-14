
(function (angular, bleedHd) {

	function BaseQuestion() {
	}

	angular.extend(BaseQuestion.prototype, {
		construct: function (scope, containerCtl) {
			this.scope = scope;
			this.parentCtl = containerCtl;

			this.data = angular.copy(this.parentCtl.result.data) || this.emptyData();
		},
		emptyData: function () {
			return {
				value: null,
				supplements: {},
			};
		},
		link: function (element) {},
		registerSupplement: function (supplement) {
			console.log("registering", supplement);
		},
	});


	function YesNoQuestion(scope, containerCtl) {
		this.construct(scope, containerCtl);

		this.options = $.extend({
			yes: { label: 'Yes', value: true },
			no: { label: 'No', value: false },
		}, containerCtl.question.options);
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
				require: '^^container',
				scope: {
					type: '=',
				},
				compile: function (element, attrs, transclude) {
					// return simple linking function that dynamically loads the question template
					return function (scope, element, attrs, containerCtl) {

						scope.questionCtl = new questionTypes[scope.type](scope, containerCtl);

						$templateRequest(bleedHd.getView('question', 'question-type-' + scope.type)).then(function (template) {
							element.append($compile(template)(scope));
							scope.questionCtl.link(element);
						});
					};
				},
			};
		});

})(angular, bleedHd);
