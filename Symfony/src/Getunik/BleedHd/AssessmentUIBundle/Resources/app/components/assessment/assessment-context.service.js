
(function (angular, bleedHd) {

	function AssessmentContext($q, patientData, AssessmentData, QuestionnaireData) {
		this.$q = $q;
		this.PatientData = patientData;
		this.AssessmentData = AssessmentData;
		this.QuestionnaireData = QuestionnaireData;
		this.assessment = null;
	}

	angular.extend(AssessmentContext.prototype, {
		load: function (patientId, assessmentId) {
			var that = this;

			if (this.assessment === null || this.assessment.id != assessmentId) {
				var self = this.$q.defer();

				// TODO: get existing responses and link with questionnaire
				return that.$q.all({
					patient: that.PatientData.getPatient(patientId),
					assessment: that.AssessmentData.getAssessment(patientId, assessmentId),
					responses: that.AssessmentData.getResponses(patientId, assessmentId),
				}).then(function (promises) {
					that.patient = promises.patient;
					that.assessment = promises.assessment;
					that.responses = promises.responses;

					return that.QuestionnaireData.get(that.assessment.questionnaire);
				}).then(function (questionnaire) {
					that.questionnaire = questionnaire;
					console.log('questionnaire', questionnaire, that.responses);

					return that;
				});
			} else {
				return that;
			}
		},
		saveResponses: function (responses) {
			return this.AssessmentData.saveResponses(this.patient.id, this.assessment.id, responses);
		},
	});


	angular.module('assessment')
		.service('AssessmentContext', AssessmentContext);

})(angular, bleedHd);
