
(function (angular, bleedHd) {

	angular.module('common')
		.directive('header', function () {
			return {
				restrict: 'E',
				scope: {
					allowLogout: '@',
					altLink: '@',
					altLabel: '@',
				},
				templateUrl: bleedHd.getView('common', 'header'),
			};
		});

})(angular, bleedHd);
