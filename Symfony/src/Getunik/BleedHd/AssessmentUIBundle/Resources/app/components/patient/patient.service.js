
(function (angular, bleedHd) {

	function PatientDataService($q, BleedApi, DateHelper) {
		this.$q = $q;
		this.BleedApi = BleedApi;
		this.DateHelper = DateHelper;
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
					assessments: this.BleedApi.one('patients', patientId).getList('assessments'),
				})
				.then(function (promises) {
					promises.patient.statuses = promises.statuses;
					promises.patient.assessments = promises.assessments;
					patient.resolve(promises.patient);
				}, function (reason) {
					patient.reject(reason);
				});

			return patient.promise;
		},
		newPatient: function () {
			return {
				is_active: true,
				birthdate: this.DateHelper.fromDate(undefined, false),
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
				transplant_date: this.DateHelper.fromDate(new Date(), false),
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
		.service('PatientData', PatientDataService);

})(angular, bleedHd);
