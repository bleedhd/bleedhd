
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
				that.Export.generate({ bla: 42, nested: { some: 'value' } }).then(function (result) {
					that.downloadLink = '/download/export/' + result.id + '/' + result.name;
					that.downloadName = result.name;
				});
			}
        },
        {
            asName: 'ctlExport',
            templateUrl: bleedHd.getView('export', 'export'),
        }
    );

})(angular, bleedHd);
