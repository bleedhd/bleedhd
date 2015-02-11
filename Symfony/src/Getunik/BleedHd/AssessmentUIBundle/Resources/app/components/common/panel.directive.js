
(function (angular, bleedHd) {

	angular.module('common')
		.directive('panel', function () {
			return {
				restrict: 'E',
				transclude: true,
				scope: {
					heading: '@',
				},
				templateUrl: bleedHd.getView('common', 'panel'),
			};
		});

})(angular, bleedHd);
