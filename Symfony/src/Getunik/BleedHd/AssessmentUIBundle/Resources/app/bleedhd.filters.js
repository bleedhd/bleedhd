
(function (angular, bleedHd) {

	angular.module('bleedHdApp')

		/**
		 * Formats the given Date object as a birthdate
		 */
		.filter('birthdate', function ($filter, BleedHdConfig) {
			var dateFilter = $filter('date');
			return function (date) {
				return dateFilter(date, BleedHdConfig.format.birthdate);
			};
		})

		.filter('boolyesno', function (BleedHdConfig) {
			return function (value) {
				return (value === true ? BleedHdConfig.format.yesno[0] : BleedHdConfig.format.yesno[1]);
			};
		});

		; // finally end the giant statement

})(angular, bleedHd);
