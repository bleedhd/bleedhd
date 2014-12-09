
(function (angular, bleedHd) {

	angular.module('bleedHdApp').config(['$routeProvider', '$stateProvider',
		function($routeProvider, $stateProvider) {
			$routeProvider
				.when('/patients', bleedHd.controllers.PatientOverviewController)
				.when('/patients/new', bleedHd.controllers.PatientEditController)
				.when('/patients/edit/:patientId', bleedHd.controllers.PatientEditController)
				.when('/patients/detail/:patientId', bleedHd.controllers.PatientDetailController)
				.when('/patients/:patientId/status/new', bleedHd.controllers.PatientStatusEditController)
				.when('/patients/:patientId/status/edit/:statusId', bleedHd.controllers.PatientStatusEditController)
				.otherwise('/patients');
		}
	]);

})(angular, bleedHd);
