
(function (angular, bleedHd) {

	angular.module('common')
		.directive('header', function () {
			return {
				restrict: 'E',
				scope: {
					allowLogout: '@',
				},
				templateUrl: bleedHd.getView('common', 'header'),
			};
		});

})(angular, bleedHd);
