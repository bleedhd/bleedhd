
(function (angular, bleedHd) {

	function PatientDataService($q, BleedApi) {
		this.$q = $q;
		this.BleedApi = BleedApi;
		this.patients = BleedApi.all('patients');
	}

	angular.extend(PatientDataService.prototype, {
		getPatients: function () {
			return this.patients.getList();
		},
		getPatient: function (patientId) {
			var patient = this.$q.defer();

			this.$q
				.all({
					patient: this.patients.get(patientId),
					statuses: this.BleedApi.one('patients', patientId).getList('statuses'),
				})
				.then(function (promises) {
					promises.patient.statuses = promises.statuses;
					patient.resolve(promises.patient);
				}, function (reason) {
					patient.reject(reason);
				});

			return patient.promise;
		},
		newPatient: function () {
			return {
				is_active: true,
			};
		},
		savePatient: function (patient) {
			if (patient.id === undefined) {
				return this.patients.post(patient);
			} else {
				return patient.put();
			}
		},
		newStatus: function (patient) {
			return {
				patient_id: patient.id,
				transplant_date: new Date(),
			};
		},
		saveStatus: function (status) {
			if (status.id === undefined) {
				return this.BleedApi.one('patients', status.patient_id).all('statuses').post(status);
			} else {
				return status.put();
			}
		},
	});

	angular.module('patient')
		.service('patientData', PatientDataService);

})(angular, bleedHd);
