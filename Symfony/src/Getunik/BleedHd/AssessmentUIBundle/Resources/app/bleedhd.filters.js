
(function (angular, bleedHd) {

	var genderMap = {
		m: 'male',
		f: 'female',
		unknown: 'Not specified',
	};

	angular.module('bleedHdApp')

		/**
		 * Formats the given Date object as an ISO-8601 date
		 */
		.filter('isodate', function ($filter, BleedHdConfig) {
			var dateFilter = $filter('date');
			return function (date) {
				return dateFilter(date, BleedHdConfig.format.isodate);
			};
		})

		.filter('boolyesno', function (BleedHdConfig) {
			return function (value) {
				return (value === true ? BleedHdConfig.format.yesno[0] : BleedHdConfig.format.yesno[1]);
			};
		})

		.filter('gender', function () {
			return function (sex) {
				return (genderMap[sex] === undefined ? genderMap.unknown : genderMap[sex]);
			}
		})

		; // finally end the giant statement

})(angular, bleedHd);
