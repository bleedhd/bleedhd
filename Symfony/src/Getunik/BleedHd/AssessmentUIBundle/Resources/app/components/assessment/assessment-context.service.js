
(function (angular, bleedHd) {

	function versionCompare(vA, vB) {
		if (!vA || !vB)
			return 0;

		var segmentsA = vA.split('.'),
			segmentsB = vB.split('.'),
			index, currentA, currentB;

		for (index = 0; index < 3; index++) {
			currentA = parseInt(segmentsA[index]) || 0;
			currentB = parseInt(segmentsB[index]) || 0;

			if (currentA < currentB) {
				return -(3 - index);
			} else if (currentA > currentB) {
				return (3 - index);
			}
		}

		return 0;
	}

	function AssessmentContext($log, $q, PatientData, AssessmentData, QuestionnaireData) {
		this.$log = $log;
		this.$q = $q;
		this.PatientData = PatientData;
		this.AssessmentData = AssessmentData;
		this.QuestionnaireData = QuestionnaireData;
		this.assessment = null;
	}

	angular.extend(AssessmentContext.prototype, {
		load: function (patientId, assessmentId) {
			var that = this;

			if (this.assessment === null || this.assessment.id != assessmentId) {
				var self = this.$q.defer();

				return that.$q.all({
					patient: that.PatientData.getPatient(patientId),
					assessment: that.AssessmentData.getAssessment(patientId, assessmentId),
					responses: that.AssessmentData.getResponses(patientId, assessmentId),
				}).then(function (promises) {
					that.patient = promises.patient;
					that.assessment = promises.assessment;

					that.responses = {};
					angular.forEach(promises.responses, function (response) {
						that.responses[response.id] = response;
					});

					return that.QuestionnaireData.get(that.assessment.questionnaire);
				}).then(function (questionnaire) {
					var vDiff = versionCompare(that.assessment.questionnaire_version, questionnaire.version);

					that.questionnaire = questionnaire;
					that.$log.debug('questionnaire', questionnaire, that.responses);

					// if the assessment was created / last edited with a questionnaire version that is at least
					// one "major point" behind, then this might mean trouble / inconsistent data
					if (vDiff < -1) {
						that.$log.warn('loading assessment with legacy questionnaire data', that.assessment.questionnaire_version, questionnaire.version);
					}

					return that;
				});
			} else {
				return that;
			}
		},
		saveResponses: function (responses) {
			var that = this;

			// update responses in current context
			angular.forEach(responses, function (response) {
				that.responses[response.id] = response;
			});

			return this.AssessmentData.saveResponses(this.patient.id, this.assessment.id, responses);
		},
		getResponseForQuestion: function (slug) {
			if (this.responses[slug.full] === undefined) {
				if (this.questionnaire.isMultiQuestion(slug)) {
					var that = this,
						multi = {};

					angular.forEach(that.questionnaire.getMultiQuestionChildSlugs(slug), function (childSlug) {
						multi[childSlug.full] =(that.responses[childSlug.full] === undefined ? that._newResponse(childSlug) : that.responses[childSlug.full]);
					});

					return multi;
				} else {
					return this._newResponse(slug);
				}
			} else {
				return this.responses[slug.full];
			}
		},
		_newResponse: function (slug) {
			return {
				id: slug.full,
				assessment_id: this.assessment.id,
				result: { data: null, meta: 'nya' },
			};
		},
	});


	angular.module('assessment')
		.service('AssessmentContext', AssessmentContext);

})(angular, bleedHd);
