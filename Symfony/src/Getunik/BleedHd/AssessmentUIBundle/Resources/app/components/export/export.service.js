
(function (angular, bleedHd) {

	function ExportService($q, BleedHttp, DateHelper, DataEvents) {
		this.$q = $q;
		this.BleedHttp = BleedHttp;
		this.DateHelper = DateHelper;
		this.DataEvents = DataEvents;
	}

	angular.extend(ExportService.prototype, {
		test: function () {
			this.BleedHttp.post('/api/export/generate',{ bla: 42, nested: { some: 'value' } });
		},
	});


	angular.module('export')

		.service('Export', ExportService)

	;

})(angular, bleedHd);
