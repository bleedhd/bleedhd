
(function (angular, bleedHd) {

	function ExportService($q, BleedApi, BleedHttp, DateHelper, DataEvents) {
		this.$q = $q;
		this.BleedApi = BleedApi;
		this.BleedHttp = BleedHttp;
		this.DateHelper = DateHelper;
		this.DataEvents = DataEvents;
	}

	angular.extend(ExportService.prototype, {
		generate: function (settings) {
			return this.BleedHttp.post('/api/export/generate', settings).then(function (response) {
				return response.data;
			});
		},
		getConfigurationMap: function () {
			return this.BleedHttp.get('/api/exportconfigs').then(function (response) {
				return response.data;
			});
		},
	});


	angular.module('export')

		.service('Export', ExportService)

	;

})(angular, bleedHd);
