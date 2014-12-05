
(function (angular, bleedHd) {

	function PatientDataService(secureResource, bleedHdConfig) {
		this.resource = secureResource;
		this.config = bleedHdConfig;

		this.resource = secureResource([this.config.api.base, this.config.api.resources.patients, ':patientId'].join('/'), { patientId: '@id' });
	}

	angular.extend(PatientDataService.prototype, {
		getPatients: function () {
			return this.resource.query();
		},
		newPatient: function () {
			return {
				is_active: true,
			};
		},
		savePatient: function (patient) {
			if (patient.id === undefined) {
				this.resource.save(patient);
			} else {

			}
		},
	});


	angular.module('patient')
		.factory('patientData', function (secureResource, bleedHdConfig) {
			return new PatientDataService(secureResource, bleedHdConfig);
		});

})(angular, bleedHd);
