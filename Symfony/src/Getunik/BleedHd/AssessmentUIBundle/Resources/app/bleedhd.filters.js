
(function (angular, bleedHd) {

	angular.module('bleedHdApp')

		/**
		 * Formats the given Date object as a birthdate
		 */
		.filter('birthdate', function ($filter, bleedHdConfig) {
			var dateFilter = $filter('date');
			return function (date) {
				return dateFilter(date, bleedHdConfig.format.birthdate);
			};
		})

		.filter('boolyesno', function (bleedHdConfig) {
			return function (value) {
				return (value === true ? bleedHdConfig.format.yesno[0] : bleedHdConfig.format.yesno[1]);
			};
		});

		; // finally end the giant statement

})(angular, bleedHd);
