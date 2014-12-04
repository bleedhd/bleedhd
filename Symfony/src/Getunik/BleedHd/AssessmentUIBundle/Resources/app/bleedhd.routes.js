
(function (angular) {

	angular.module('bleedHdApp').config(['$routeProvider', '$stateProvider',
		function($routeProvider, $stateProvider) {
			console.log("config");
			$routeProvider
				.when('/patients', {
					templateUrl: 'GetunikBleedHdAssessmentUIBundle/Resources/app/components/patient/overview.view.html',
					controller: 'PatientOverviewController',
				})
				.otherwise('/patients');
		}
	]);

})(angular);
