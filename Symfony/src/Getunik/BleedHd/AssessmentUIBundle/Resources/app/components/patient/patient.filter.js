
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
		'gvhd-features': 'GVHD features',
		'gvhd-new-diagnosis': 'GVHD new diagnosis',
		'gvhd-organ-scoring': 'GVHD organ scoring',
		'gvhd-activity-assessment': 'GVHD activity assessment',
		'gvhd-self-report': 'GVHD patient self report',
		'gvhd-late-onset-acute': 'Late-onset aGVHD classification',
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

		.filter('progress', function (DomainConst) {
			return function (progress) {
				if (progress === DomainConst.progress.none)
					return '';

				return !progress ? 'Tentative' : progress.charAt(0).toUpperCase() + progress.substr(1);
			};
		})

		;

})(angular);
