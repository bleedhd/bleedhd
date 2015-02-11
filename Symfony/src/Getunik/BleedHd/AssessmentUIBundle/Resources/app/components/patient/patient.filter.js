
(function (angular) {

	angular.module('patient')

		/**
		 * Formats the given Date object as an ISO-8601 date
		 */
		.filter('patientname', function () {
			return function (patient) {
				return  [patient.lastname, patient.firstname].join(' ');
			};
		})

		;

})(angular);
