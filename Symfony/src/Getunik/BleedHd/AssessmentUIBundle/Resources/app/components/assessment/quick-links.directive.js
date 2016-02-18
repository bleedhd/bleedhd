
(function (angular, bleedHd) {

	function QuickLinkController($scope, $element, $attrs, $transclude) {

		var ctl = this;

		$scope.$watch('assessmentContext', function (newValue, oldValue) {
			if (newValue !== oldValue) {
				ctl._loadQuickLinks(newValue);
			}
		});

		ctl._loadQuickLinks($scope.assessmentContext);
	}

	angular.extend(QuickLinkController.prototype, {
		_loadQuickLinks: function (context) {
			this.context = context;
			this.quickLinks = context.getQuickLinks();
		}
	});

	angular.module('assessment')
		.directive('quickLinks', function() {
			return {
				restrict: 'E',
				scope: {
					assessmentContext: '=',
				},
				templateUrl: bleedHd.getView('assessment', 'quick-links'),
				controllerAs: 'quickLinkCtl',
				controller: QuickLinkController,
			};
		});

})(angular, bleedHd);
