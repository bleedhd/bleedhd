
(function (angular, bleedHd) {

	angular.module('bleedHdApp').config(['$routeProvider', '$stateProvider',
		function($routeProvider, $stateProvider) {
			console.log("config");
			$routeProvider
				.when('/patients', {
					templateUrl: bleedHd.getView('patient', 'overview'),
				})
				.otherwise('/patients');
		}
	]);

})(angular, bleedHd);
