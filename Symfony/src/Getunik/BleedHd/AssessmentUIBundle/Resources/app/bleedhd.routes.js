
(function (angular, bleedHd) {

	angular.module('bleedHdApp').config(['$routeProvider', '$stateProvider',
		function($routeProvider, $stateProvider) {
			$routeProvider
				.when('/patients', bleedHd.controllers.PatientOverviewController)
				.otherwise('/patients');
		}
	]);

})(angular, bleedHd);
