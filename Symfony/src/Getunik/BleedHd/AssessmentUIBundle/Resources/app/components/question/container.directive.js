
(function (angular, bleedHd) {

	function AssessmentQuestionContainerController($scope) {
		this.scope = $scope;
		this.question = this.scope.question();
		this.response = this.scope.response();
		this.result = this.response.result;

		// set this scope property to bind to in the view. this needs to be in an object because the
		// ng-model binding inside an ng-repeat appears to not work otherwise.
		this.scope.binding = { meta: this.getInitialMetaValue() };

		var that = this;

		$scope.$on('q-data-changed', function (event, questionCtl) {
			var currentResponse = that.response;

			// switch for multi-question handling
			if (that.result === undefined) {
				currentResponse = that.response[questionCtl.slug.full];
			}

			currentResponse.result.meta = null;
			currentResponse.result.data = questionCtl.data;

			that.scope.binding.meta = null;
			that.scope.$emit('q-response-changed', currentResponse);
		});

		/*$scope.$watch('containerCtl.result.meta', function (newValue, oldValue) {
			if (newValue !== oldValue && newValue !== null) {
				var currentResult = that.result;

				// switch for multi-question handling
				if (that.result === undefined) {
					angular.forEach(that.response, function (response) {
						response.result.data = null;
					});
					currentResult = that.response[questionCtl.slug.full];
				} else {
					that.result.data = null;
				}

				that.resetQuestions();
				$scope.$emit('q-response-changed', that.response);
			}
		});*/
	}

	angular.extend(AssessmentQuestionContainerController.prototype, {
		getInitialMetaValue: function () {
			var that = this;

			if (that.result === undefined) {
				var meta = '';
				angular.forEach(that.response, function (response) {
					if (meta !== null) {
						if (response.result.meta === null || (meta !== '' && meta !== response.result.meta)) {
							meta = null;
						} else {
							meta = response.result.meta;
						}
					}
				});

				return meta;
			}

			return that.result.meta;
		},
		resetQuestions: function () {
			this.scope.$broadcast('q-do-reset');
		},
		onMetaChange: function () {
			var that = this;

			that.resetQuestions();

			// switch for multi-question handling
			if (that.result === undefined) {
				angular.forEach(that.response, function (response) {
					response.result.data = null;
					response.result.meta = that.scope.binding.meta;
					that.scope.$emit('q-response-changed', response);
				});
			} else {
				that.result.data = null;
				that.result.meta = that.scope.binding.meta;
				that.scope.$emit('q-response-changed', that.response);
			}
		},
	});


	angular.module('question')
		.directive('container', function () {
			return {
				templateUrl: bleedHd.getView('question', 'container'),
				restrict: 'E',
				scope: {
					question: '&data',
					response: '&',
				},
				controller: AssessmentQuestionContainerController,
				controllerAs: 'containerCtl',
				link: function (scope, element, attrs, controller) {
				},
			};
		});

})(angular, bleedHd);
