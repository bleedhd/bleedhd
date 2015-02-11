
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
				p = this._getPatient(patientId);

			return this.$q
				.all({
					patient: p,
					additions: this._getPatientAdditions(patientId),
				})
				.then(function (promises) {
					promises.patient.statuses = promises.additions.statuses;
					promises.patient.assessments = promises.additions.assessments;
					return promises.patient;
				});
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
		_getPatient: function (patientId) {
			return this.BleedApi.one('patients', patientId).get();
		},
		_getPatientAdditions: function (patientId) {
			var base = this.BleedApi.one('patients', patientId);
			return this.$q
				.all({
					statuses: base.getList('statuses'),
					assessments: base.getList('assessments'),
				});
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
						func: '_getPatient',
						key: function (patientId) { return 'patient-' + patientId; },
					},
					{
						func: '_getPatientAdditions',
						key: function (patientId) { return 'patient-additions-' + patientId; },
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

								// since the update events are triggered before the server request is sent,
								// if we add a new patient, it will be missing its ID, so we just wipe the
								// cache in this case.
								if (!found) {
									that.caches.default.remove('patients');
								}
							});
						}
					});

					// Saving responses has the side effect of recalculating (and storing) the assessment score.
					// As a result, the patient (linked with assessments) has to be reloaded
					DataEvents.on('responses-update', function (event) {
						that.caches.default.remove(['patient', event.patientId].join('-'));
						that.caches.default.remove(['patient-additions', event.patientId].join('-'));
					});
					DataEvents.on(['status-update', 'assessment-update'], function (event) {
						that.caches.default.remove(['patient', event.data.patient_id].join('-'));
						that.caches.default.remove(['patient-additions', event.data.patient_id].join('-'));
					});

					// Status objects are always accessed from the patient - they are never retrieved or modified
					// directly - and should therefore always be in a consistent state.
				}
			);
		})

	;

})(angular, bleedHd);
