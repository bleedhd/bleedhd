(function (angular, bleedHd) {

	function ExportController($scope, Export, PatientData, DateHelper, BleedHdConfig, DomainConst, patient) {
		this.$scope = $scope;
		this.Export = Export;
		this.PatientData = PatientData;
		this.BleedHdConfig = BleedHdConfig;
		this.DomainConst = DomainConst;
		this.patient = patient;
		this.exportFile = null;

		this.exportConfigMap = Export.getConfigurationMap();
		this.availableExportConfigs = null;
		this.availableQuestionnaires = null;
		this.assessmentCounts = {};
		this.exportModal = angular.element('#modal_export_process');

		// this.datepickerOptions = {
		// 	minDate: DateHelper.fromString('2000-01-01'),
		// 	maxDate: DateHelper.fromDate(new Date()),
		// 	startingDay: 1,
		// };

		this.filter = {
			patient: {
				active: true,
			},
			assessment: {
				progress: this.DomainConst.progress.completed,
				startFrom: undefined,
				startTo: undefined,
			},
		};

		this.exportSettings = {
			baseName: 'export-' + moment().format('YYYYMMDD-HHmmss'),
			filters: [],
			typeMap: [
				{
					questionnaire: null,
					export: 'default',
				}
			],
		};

		this.typeMap = this.exportSettings.typeMap[0];

		this.onFilterChange();
	}

	bleedHd.registerController('export', ExportController,
		{
			generate: function () {
				var that = this;

				that.exportModal.modal('show');

				that.Export.generate(that.exportSettings).then(function (result) {
					that.exportModal.modal('hide');
					if (result.status === 'ok') {
						that.exportFile = {
							id: result.id,
							name: result.name,
							url: ['/download/export', result.id, result.name].join('/'),
						};
					}
				});
			},
			onFilterChange: function () {
				var filters = [];

				if (this.patient) {
					filters.push({
						target: 'patient',
						property: 'id',
						op: 'eq',
						value: this.patient.id,
					});
				} else if (this.filter.patient.active === true) {
					filters.push({
						target: 'patient',
						property: 'isActive',
						op: 'eq',
						value: true,
					});
				}

				if (this.filter.assessment.progress === this.DomainConst.progress.completed) {
					filters.push({
						target: 'assessment',
						property: 'progress',
						op: 'eq',
						value: this.DomainConst.progress.completed,
					});
				}

				if (this.filter.assessment.startFrom) {
					filters.push({
						target: 'assessment',
						property: 'startDate',
						op: 'gte',
						value: this.filter.assessment.startFrom.toJSON(),
					});
				}

				if (this.filter.assessment.startTo) {
					filters.push({
						target: 'assessment',
						property: 'startDate',
						op: 'lte',
						value: this.filter.assessment.startTo.toJSON(),
					});
				}

				this.exportSettings.filters = filters;

				var that = this;

				that.filteredAssessmentCount = '?';
				that.Export.count(that.exportSettings).then(function (count) {
					that.assessmentCounts = {total: 0};
					that.availableQuestionnaires = [];

					angular.forEach(count, function (row) {
						var count = parseInt(row.num);
						that.assessmentCounts[row.questionnaire] = count;
						that.assessmentCounts.total += count;
						that.availableQuestionnaires.push({
							key: row.questionnaire,
							count: count,
							name: that.DomainConst.questionnaires[row.questionnaire],
						});
					});

					that.availableQuestionnaires.sort(function (a, b) {
						return a.name > b.name;
					});

					if (that.assessmentCounts[that.typeMap.questionnaire] === undefined) {
						that.typeMap.questionnaire = null;
					}

					that.onQuestionnaireChange();
				});
			},
			onQuestionnaireChange: function () {
				var that = this;

				that.filteredAssessmentCount = that.typeMap.questionnaire ? that.assessmentCounts[that.typeMap.questionnaire] : that.assessmentCounts.total;

				that.exportConfigMap.then(function (map) {
					if (!that.typeMap.questionnaire || !map[that.typeMap.questionnaire]) {
						that.availableExportConfigs = null;
						that.typeMap.export = null;
					} else {
						that.availableExportConfigs = map[that.typeMap.questionnaire];
						that.typeMap.export = that.availableExportConfigs[0] ? that.availableExportConfigs[0].key : null;
					}
				});
			},
		},
		{
			asName: 'ctlExport',
			templateUrl: bleedHd.getView('export', 'export'),
			resolve: {
				patient: function ($route, PatientData) {
					return $route.current.params.patientId ? PatientData.getPatient($route.current.params.patientId) : null;
				},
			},
		}
	);

})(angular, bleedHd);
