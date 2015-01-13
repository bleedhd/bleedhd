
(function (angular, bleedHd) {

	function AssessmentDataService($q, BleedApi) {
		this.$q = $q;
		this.BleedApi = BleedApi;
	}

	angular.extend(AssessmentDataService.prototype, {
		getAssessment: function (patientId, assessmentId) {
			return this.BleedApi.one('patients', patientId).all('assessments').get(assessmentId);
		},
		newAssessment: function (patientId) {
			return {
				patient_id: patientId,
				start_date: new Date(),
				start_time: new Date(),
			};
		},
		saveAssessment: function (assessment) {
			if (assessment.id === undefined) {
				return this.BleedApi.one('patients', assessment.patient_id).all('assessments').post(assessment);
			} else {
				return assessment.put();
			}
		},
		getResponses: function (patientId, assessmentId) {
			return this.BleedApi.one('patients', patientId).one('assessments', assessmentId).all('responses').getList();
		},
		saveResponses: function (patientId, assessmentId, responses) {
			var that = this;

			angular.forEach(responses, function (response) {
				if (response.id === undefined) {
					that.BleedApi.one('patients', patientId).one('assessments', assessmentId).all('responses').post(response);
				} else {
					response.put();
				}
			});
		},
	});


	angular.module('assessment')
		.service('AssessmentData', AssessmentDataService);

})(angular, bleedHd);
