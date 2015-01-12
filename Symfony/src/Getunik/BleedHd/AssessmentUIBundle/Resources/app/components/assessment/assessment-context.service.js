
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

			if (this.assessment === null || this.assessment.id != assessment.id ) {
				var self = this.$q.defer();

				// TODO: get existing responses and link with questionnaire
				return that.$q.all({
					patient: that.PatientData.getPatient(patientId),
					assessment: that.AssessmentData.getAssessment(patientId, assessmentId),
				}).then(function (promises) {
					that.patient = promises.patient;
					that.assessment = promises.assessment;

					return that.QuestionnaireData.get(that.assessment.questionnaire);
				}).then(function (questionnaire) {
					that.questionnaire = questionnaire;

					return that;
				});
			} else {
				return that;
			}
		},
	});


	angular.module('assessment')
		.service('AssessmentContext', AssessmentContext);

})(angular, bleedHd);
