
(function (angular, bleedHd) {

	angular.module('patient')
		.controller('PatientOverviewController', function ($scope) {
			console.log("patient overview");
			$scope.data = [
				'good',
				'bad',
				'good',
				'dead'
			];
		});

})(angular, bleedHd);
