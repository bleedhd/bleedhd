
(function (angular, bleedHd) {

	function ExportService($q, BleedHttp, DateHelper, DataEvents) {
		this.$q = $q;
		this.BleedHttp = BleedHttp;
		this.DateHelper = DateHelper;
		this.DataEvents = DataEvents;
	}

	angular.extend(ExportService.prototype, {
		generate: function (settings) {
			return this.BleedHttp.post('/api/export/generate', settings).then(function (response) {
				console.log("export response", response);
				return response.data;
			});
		},
	});


	angular.module('export')

		.service('Export', ExportService)

	;

})(angular, bleedHd);
