
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
			this.DataEvents.trigger('status-update', status);
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
					},
					{
						func: 'getPatient',
						key: function (patientId) { return 'patient-' + patientId; },
					},
					{
						type: 'save',
						func: 'savePatient',
						key: function (patient) { return 'patient-' + patient.id; },
					},
				],
				function () {
					var that = this;

					// when a single patient is saved, the patient list would normally be outdated
					DataEvents.on('patient-update', function (event) {
						var cacheEntry = that.caches.default.get('patients'),
							found = false;

						if (cacheEntry) {
							// to get to the actual data array, we need to do it the restangular way
							cacheEntry.obj.then(function (patients) {
								angular.forEach(patients, function (value, index) {
									if (event.data.id === value.id) {
										found = true;
										patients[index] = event.data;
									}
								});

								if (!found) {
									patients.push(event.data);
								}
							});
						}
					});

					// Saving responses has the side effect of recalculating (and storing) the assessment score.
					// As a result, the patient (linked with assessments) has to be reloaded
					DataEvents.on('responses-update', function (event) {
						that.caches.default.remove(['patient', event.patientId].join('-'));
					});

					// Status objects are always accessed from the patient - they are never retrieved or modified
					// directly - and should therefore always be in a consistent state.
				}
			);
		})

	;

})(angular, bleedHd);
