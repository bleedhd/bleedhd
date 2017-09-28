
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

		.filter('assessmentResource', function (BleedHdConfig) {
			return function (path) {
				return path.match(/^https?:\/\//) || path.match(/^\//) ? path : [BleedHdConfig.assessmentResourcesPath, path].join('/');
			};
		})

		.filter('gender', function (DomainConst) {
			return function (sex) {
				return (DomainConst.genders[sex] === undefined ? DomainConst.genders.unknown : DomainConst.genders[sex]);
			};
		})

		.filter('toQName', function (DomainConst) {
			return function (name) {
				return DomainConst.questionnaires[name] || name;
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
