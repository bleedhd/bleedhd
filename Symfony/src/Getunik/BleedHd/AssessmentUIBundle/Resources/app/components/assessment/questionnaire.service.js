
(function (angular, bleedHd) {

	function QuestionnaireDataService($q, BleedApi) {
		this.$q = $q;
		this.BleedApi = BleedApi;
		this.questionnaires = BleedApi.all('questionnaires');
	}

	angular.extend(QuestionnaireDataService.prototype, {
		get: function (name) {
			return this.questionnaires.get(name);
		},
		processYaml: function (yamlData) {

		},
	});


	angular.module('assessment')
		.service('QuestionnaireData', QuestionnaireDataService);

})(angular, bleedHd);
