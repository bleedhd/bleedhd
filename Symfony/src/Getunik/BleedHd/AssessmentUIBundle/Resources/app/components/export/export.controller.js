
(function (angular, bleedHd) {

    function ExportController($scope, Export, PatientData, BleedHdConfig) {
        this.$scope = $scope;
		this.Export = Export;
        this.PatientData = PatientData;
        this.BleedHdConfig = BleedHdConfig;
    }

    bleedHd.registerController('export', ExportController,
        {
			generate: function () {
				this.Export.test();
			}
        },
        {
            asName: 'ctlExport',
            templateUrl: bleedHd.getView('export', 'export'),
        }
    );

})(angular, bleedHd);
