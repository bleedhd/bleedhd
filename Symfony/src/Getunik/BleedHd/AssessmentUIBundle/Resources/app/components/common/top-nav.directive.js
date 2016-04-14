
(function (angular, bleedHd) {

	function TopNavController($scope, $element, $attrs, $transclude, $rootScope) {
		this.linkClasses = {
			'btn': true,
			'btn-default': true,
			'pull-right': true,
		};

		if ($scope.navScope) {
			this.linkClasses['scope-' + $scope.navScope] = true;
		}
	}

	angular.module('common')
		.directive('topNav', function () {
			return {
				restrict: 'E',
				scope: {
					title: '@',
					navUrl: '@',
					navLabel: '@',
					navScope: '@',
				},
				templateUrl: bleedHd.getView('common', 'top-nav'),
				controllerAs: 'topNavCtl',
				controller: TopNavController,
			};
		});

})(angular, bleedHd);
