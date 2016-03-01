
(function (angular, bleedHd) {

	angular.module('question')
		.directive('questionLink', function ($compile, BleedHdConfig) {
			return {
				restrict: 'E',
				template: '<a href="" ng-click="goToQuestion(slug)" ng-bind-html="label"></a>',
				scope: {
					label: '@',
					slug: '@',
				},
				link: function (scope, element, attrs) {
					scope.goToQuestion = function (questionSlug) {
						scope.$emit('question-link-goto', questionSlug);
					};
				},
			};
		});

})(angular, bleedHd);
