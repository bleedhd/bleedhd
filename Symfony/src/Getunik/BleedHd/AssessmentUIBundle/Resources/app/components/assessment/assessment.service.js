
(function (angular, bleedHd) {

	function AssessmentDataService($q, $rootScope, BleedApi, QuestionnaireData, UserData, DateHelper, DataEvents, DomainConst) {
		this.$q = $q;
		this.env = $rootScope.env;
		this.BleedApi = BleedApi;
		this.DateHelper = DateHelper;
		this.DataEvents = DataEvents;
		this.QuestionnaireData = QuestionnaireData;
		this.UserData = UserData;
		this.DomainConst = DomainConst;
	}

	angular.extend(AssessmentDataService.prototype, {
		getAssessment: function (patientId, assessmentId) {
			var that = this;
			return that.BleedApi.one('patients', patientId).all('assessments').get(assessmentId).then(function (assessment) {
				return that.UserData.getUser(assessment.created_by).then(function (user) {
					assessment.creator = user;
					return assessment;
				});
			});
		},
		newAssessment: function (patientId) {
			var that = this;
			return that.UserData.getUser(that.env.uid).then(function (user) {
				return {
					patient_id: patientId,
					start_date: that.DateHelper.fromDate(new Date(), true),
					progress: that.DomainConst.progress.tentative,
					creator: user,
				};
			});
		},
		saveAssessment: function (assessment) {
			this.DataEvents.trigger('assessment-update', assessment);
			if (assessment.id === undefined) {
				return this.BleedApi.one('patients', assessment.patient_id).all('assessments').post(assessment);
			} else {
				return assessment.put();
			}
		},
		deleteAssessment: function (assessment) {
			this.DataEvents.trigger('assessment-delete', assessment);
			return this.BleedApi.one('patients', assessment.patient_id).one('assessments', assessment.id).remove();
		},
		getResponses: function (patientId, assessmentId) {
			return this.BleedApi.one('patients', patientId).one('assessments', assessmentId).all('responses').getList();
		},
		saveResponses: function (patientId, assessmentId, responses) {
			this.DataEvents.trigger('responses-update', responses, {
				patientId: patientId,
				assessmentId: assessmentId,
			});

			return this.BleedApi.one('patients', patientId).one('assessments', assessmentId).all('responses').customPOST(responses, 'batch');
		},
	});


	angular.module('assessment')

		.service('RawAssessmentData', AssessmentDataService)

		.service('AssessmentData', function (CachingWrapper, RawAssessmentData, DataEvents) {
			return CachingWrapper(RawAssessmentData, [
					{
						func: 'getAssessment',
						key: function (patientId, assessmentId) { return ['assessment', patientId, assessmentId].join('-'); },
					},
					{
						type: 'save',
						func: 'saveAssessment',
						key: function (assessment) { return ['assessment', assessment.patient_id, assessment.id].join('-'); },
					},
					// The getResponses and saveResponses functions are deliberately not cached. The functions
					// are only called by the AssessmentContext service which is itself already a caching mechanism.
				],
				function () {
					var that = this;

					// Saving responses has the side effect of recalculating (and storing) the assessment score on the
					// server side. By evicting the assessment from the cache, we ensure that it is properly reloaded
					// the next time it is needed.
					DataEvents.on('responses-update', function (event) {
						that.caches.default.remove(['assessment', event.patientId, event.assessmentId].join('-'));
					});
				}
			);
		})

	;

})(angular, bleedHd);
