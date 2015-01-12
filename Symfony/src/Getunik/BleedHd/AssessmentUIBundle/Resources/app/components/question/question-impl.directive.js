
(function (angular, bleedHd) {

	function BaseQuestionImpl() {
	}

	angular.extend(BaseQuestionImpl.prototype, {
		construct: function (scope, questionCtl) {
			this.scope = scope;
			this.parentCtl = questionCtl;

			this.result = {};
		},
		link: function (element) {},
	});


	function YesNoQuestion(scope, questionCtl) {
		this.construct(scope, questionCtl);
	}

	angular.extend(YesNoQuestion.prototype, BaseQuestionImpl.prototype, {
		link: function (element) {
			this.scope.$watch('implCtl.result.value', function (newValue, oldValue) {
				console.log('result changed', newValue);
			});
		},
	});


	var questionTypes = {
		yesno: YesNoQuestion,
	};


	//////

	angular.module('question')
		.directive('questionImpl', function ($templateRequest, $compile) {
			return {
				restrict: 'E',
				require: '^^question',
				scope: {
					type: '=',
				},
				//controller: QuestionImplController,
				//controllerAs: 'implCtl',
				compile: function (element, attrs, transclude) {
					// return simple linking function that dynamically loads the question template
					return function (scope, element, attrs, questionCtl) {

						scope.implCtl = new questionTypes[scope.type](scope, questionCtl);

						$templateRequest(bleedHd.getView('question', 'question-type-' + scope.type)).then(function (template) {
							element.append($compile(template)(scope));
							scope.implCtl.link(element);
						});
					};
				},
			};
		});

})(angular, bleedHd);
