
(function (angular, bleedHd) {

	var genderMap = {
		m: 'male',
		f: 'female',
		unknown: 'Not specified',
	},

	questionnaireMap = {
		'demo': 'Demo Assessment',
		'who': 'Bleeding WHO',
		'bsms': 'Bleeding BSMS',
		'gvhd-features': 'GvHD features',
		'gvhd-first-diagnosis': 'GvHD first diagnosis',
		'gvhd-current-staging': 'GvHD current staging',
		'gvhd-therapy-response': 'GvHD therapy response',
		'gvhd-self-report': 'GvHD patient self report',
	};

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

		.filter('gender', function () {
			return function (sex) {
				return (genderMap[sex] === undefined ? genderMap.unknown : genderMap[sex]);
			};
		})

		.filter('score', function () {
			return function (result) {
				return (result.score !== undefined && result.score.total !== undefined ? result.score.total : 'pending');
			};
		})

		.filter('toQName', function () {
			return function (name) {
				return questionnaireMap[name] || 'unknown';
			};
		})

		; // finally end the giant statement

})(angular, bleedHd);
