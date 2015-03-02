
(function (angular) {

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

	angular.module('patient')

		/**
		 * Formats the given Date object as an ISO-8601 date
		 */
		.filter('patientname', function () {
			return function (patient) {
				return  [patient.lastname, patient.firstname].join(' ');
			};
		})

		.filter('assessmentResource', function (BleedHdConfig) {
			return function (path) {
				return path.match(/^https?:\/\//) || path.match(/^\//) ? path : [BleedHdConfig.assessmentResourcesPath, path].join('/');
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

		.filter('progress', function () {
			return function (progress) {
				if (progress === 'none')
					return '';

				return !progress ? 'Tentative' : progress.charAt(0).toUpperCase() + progress.substr(1);
			};
		})

		;

})(angular);
