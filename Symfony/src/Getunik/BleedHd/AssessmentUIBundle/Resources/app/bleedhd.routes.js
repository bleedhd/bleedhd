
(function (angular, bleedHd) {

	angular.module('bleedHdApp').config(['$routeProvider', '$stateProvider',
		function($routeProvider, $stateProvider) {
			$routeProvider
				.when('/patients', bleedHd.controllers.PatientOverviewController)
				.when('/patients/new', bleedHd.controllers.PatientEditController)
				.when('/patients/edit/:patientId', bleedHd.controllers.PatientEditController)
				.when('/patients/detail/:patientId', bleedHd.controllers.PatientDetailController)
				.otherwise('/patients');
		}
	]);

})(angular, bleedHd);
