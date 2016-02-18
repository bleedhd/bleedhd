
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

	function AssessmentContext(AssessmentData, patient, assessment, questionnaire, responses) {
		this.AssessmentData = AssessmentData;
		this.patient = patient;
		this.assessment = assessment;
		this.questionnaire = questionnaire;
		this.responses = responses;

		// quick-links are lazy-loaded since they require some processing
		this.quickLinks = null;
	}

	angular.extend(AssessmentContext.prototype, {
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
						multi[childSlug.full] = (that.responses[childSlug.full] === undefined ? that._newResponse(childSlug) : that.responses[childSlug.full]);
					});

					return multi;
				} else {
					return this._newResponse(slug);
				}
			} else {
				return this.responses[slug.full];
			}
		},
		getQuickLinks: function () {
			var that = this;

			if (that.quickLinks === null) {
				that.quickLinks = [];

				angular.forEach(that.questionnaire.quickLinks, function (link) {
					var screen = that.questionnaire.getScreenBySlug(link.screen),
						completed;

					if (link.question) {
						completed = that.isResponseComplete(screen.slug.getChild(link.question));
					} else {
						completed = screen.questions.every(function (question) {
							return that.isResponseComplete(question.slug);
						});
					}

					that.quickLinks.push(angular.extend({ isCompleted: completed }, link));
				});
			}

			return that.quickLinks;
		},
		isResponseComplete: function (slug) {
			var that = this,
				response = that.getResponseForQuestion(slug),
				completed = true;

			if (response === undefined) {
				return false;
			}

			if (response.result === undefined) {
				// multi question
				angular.forEach(response, function (subResponse) {
					completed = completed && that._isQuestionComplete(subResponse.result);
				});
			} else {
				completed = that._isQuestionComplete(response.result);
			}

			return completed;
		},
		_newResponse: function (slug) {
			return {
				id: slug.full,
				assessment_id: this.assessment.id,
				result: { data: null, meta: 'nya' },
			};
		},
		_isQuestionComplete: function (result) {
			if (result.meta === 'nya') {
				return false;
			} else if (angular.isArray(result.data)) {
				return result.data.every(function (value) {
					// check every value - if the question has the confirmation supplement and the
					// confirmation is still pending, then the result is incomplete
					return !(angular.isObject(value.supplements) && value.supplements.confirmation === 'pending');
				});
			}

			return true;
		},
	});


	function AssessmentContextFactory($log, $q, PatientData, AssessmentData, QuestionnaireData) {
		// to avoid superfluous reconstruction of the same assessment context (which is a common
		// scenario when opening an assessment), we simply cache the last retrieved assessment
		// context.
		var lastAssessmentContext = null;

		return function (patientId, assessmentId) {
			var patient, assessment, responses;

			if (lastAssessmentContext !== null && lastAssessmentContext.assessment.id === assessmentId) {
				return lastAssessmentContext;
			}

			return $q.all({
				patient: PatientData.getPatient(patientId),
				assessment: AssessmentData.getAssessment(patientId, assessmentId),
				responses: AssessmentData.getResponses(patientId, assessmentId),
			}).then(function (promises) {
				patient = promises.patient;
				assessment = promises.assessment;

				responses = {};
				angular.forEach(promises.responses, function (response) {
					responses[response.id] = response;
				});

				return QuestionnaireData.get(assessment.questionnaire);
			}).then(function (questionnaire) {
				var vDiff = versionCompare(assessment.questionnaire_version, questionnaire.version);

				$log.debug('questionnaire', questionnaire, responses);

				// if the assessment was created / last edited with a questionnaire version that is at least
				// one "major point" behind, then this might mean trouble / inconsistent data
				if (vDiff < -1) {
					$log.warn('loading assessment with legacy questionnaire data', assessment.questionnaire_version, questionnaire.version);
				}

				lastAssessmentContext = new AssessmentContext(AssessmentData, patient, assessment, questionnaire, responses);
				return lastAssessmentContext;
			});
		};
	}


	angular.module('assessment')
		.factory('AssessmentContext', AssessmentContextFactory);

})(angular, bleedHd);
