
(function (angular, bleedHd) {

	function PatientDataService($q, BleedApi, DateHelper, DataEvents) {
		this.$q = $q;
		this.BleedApi = BleedApi;
		this.DateHelper = DateHelper;
		this.DataEvents = DataEvents;
		this.patients = BleedApi.all('patients');
	}

	angular.extend(PatientDataService.prototype, {
		getPatients: function () {
			return this.patients.getList();
		},
		getPatient: function (patientId) {
			var patient = this.$q.defer(),
				p = this.BleedApi.one('patients', patientId);

			this.$q
				.all({
					patient: p.get(),
					statuses: p.getList('statuses'),
					assessments: p.getList('assessments'),
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
			this.DataEvents.trigger('patient-update', patient);
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

		.service('RawPatientData', PatientDataService)

		.service('PatientData', function (CachingWrapper, RawPatientData, DataEvents) {
			return CachingWrapper(RawPatientData, [
					{
						func: 'getPatients',
						key: function () { return 'patients'; },
						lifetime: 60,
					},
					{
						func: 'getPatient',
						key: function (patientId) { return 'patient-' + patientId; },
						lifetime: 60,
					},
					{
						type: 'save',
						func: 'savePatient',
						key: function (patient) { console.log('args', patient); return 'patient-' + patient.id; },
						lifetime: 60,
					},
				],
				function () {
					var that = this;
					DataEvents.on('patient-update', function (event) {
						that.caches.default.remove('patients');
						console.log(event.name, event.data);
					});
				}
			);
		})

	;

})(angular, bleedHd);
