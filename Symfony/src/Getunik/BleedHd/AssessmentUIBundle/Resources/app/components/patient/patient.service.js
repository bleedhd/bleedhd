
(function (angular, bleedHd) {

	function PatientDataService(BleedApi, secureResource, bleedHdConfig) {
		this.resource = secureResource;
		this.config = bleedHdConfig;

		this.BleedApi = BleedApi;
		this.patients = BleedApi.all('patients');
	}

	angular.extend(PatientDataService.prototype, {
		getPatients: function () {
			return this.patients.getList();
		},
		getPatient: function (patientId) {
			return this.patients.get(patientId);
		},
		newPatient: function () {
			return {
				is_active: true,
			};
		},
		savePatient: function (patient) {
			if (patient.id === undefined) {
				this.patients.post(patient);
			} else {
				patient.put();
			}
		},
		getStatuses: function (patientId) {
			return this.BleedApi.one('patients', patientId).getList('statuses');
		},
	});

	angular.module('patient')
		.service('patientData', PatientDataService);

})(angular, bleedHd);
