
(function (angular, bleedHd) {

    function ExportController($scope, PatientData, BleedHdConfig) {
        this.$scope = $scope;
        this.PatientData = PatientData;
        this.BleedHdConfig = BleedHdConfig;
    }

    bleedHd.registerController('export', ExportController,
        {

        },
        {
            asName: 'ctlExport',
            templateUrl: bleedHd.getView('export', 'export'),
        }
    );

})(angular, bleedHd);
