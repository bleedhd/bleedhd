
(function (angular, bleedHd) {

    function ExportController($scope, Export, PatientData, BleedHdConfig) {
        this.$scope = $scope;
		this.Export = Export;
        this.PatientData = PatientData;
        this.BleedHdConfig = BleedHdConfig;

		this.downloadLink = null;
		this.downloadName = '[empty]';
    }

    bleedHd.registerController('export', ExportController,
        {
			generate: function () {
				var that = this;
				that.Export.generate({
					baseName: 'sample-export-' + moment().format('YYYYMMDD-HHmmss'),
					filters: [
						{
							target: 'assessment',
							property: 'startDate',
							op: 'gt',
							value: '2016-05-05',
						},
						{
							target: 'patient',
							property: 'isActive',
							op: 'eq',
							value: true,
						},
						{
							target: 'patient',
							property: 'lastname',
							op: 'like',
							value: '%Osteron%',
						},
					],
					typeMap: [
						{
							questionnaire: 'who',
							export: 'default',
						}
					],
				}).then(function (result) {
					if (result.status === 'ok') {
						that.downloadLink = '/download/export/' + result.id + '/' + result.name;
						that.downloadName = result.name;
					}
				});
			}
        },
        {
            asName: 'ctlExport',
            templateUrl: bleedHd.getView('export', 'export'),
        }
    );

})(angular, bleedHd);
