
(function (angular, bleedHd) {

    function ExportController($scope, Export, PatientData, DateHelper, BleedHdConfig, DomainConst) {
        this.$scope = $scope;
		this.Export = Export;
        this.PatientData = PatientData;
        this.BleedHdConfig = BleedHdConfig;
		this.DomainConst = DomainConst;
		this.exportFile = null;

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

		this.onFilterChange();
    }

    bleedHd.registerController('export', ExportController,
        {
			generate: function () {
				var that = this;
				that.Export.generate(that.exportSettings).then(function (result) {
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

				if (this.filter.patient.active === true) {
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
			},
        },
        {
            asName: 'ctlExport',
            templateUrl: bleedHd.getView('export', 'export'),
        }
    );

})(angular, bleedHd);
