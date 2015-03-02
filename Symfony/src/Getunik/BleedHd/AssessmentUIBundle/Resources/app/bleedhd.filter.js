
(function (angular) {

	angular.module('bleedHdApp')

		.filter('resourcePath', function (BleedHdConfig) {
			return function (path) {
				return [BleedHdConfig.resourcesPath, path].join('/');
			};
		})

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

		; // finally end the giant statement

})(angular);
