
(function (angular, bleedHd) {

	angular.module('common')
		.directive('topNav', function () {
			return {
				restrict: 'E',
				scope: {
					title: '@',
					navUrl: '@',
					navLabel: '@'
				},
				templateUrl: bleedHd.getView('common', 'top-nav'),
			};
		});

})(angular, bleedHd);
