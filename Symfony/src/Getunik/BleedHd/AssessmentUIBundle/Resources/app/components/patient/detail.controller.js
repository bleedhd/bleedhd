
(function (angular, bleedHd) {

	function PatientDetailController($scope, $routeParams, PatientData, BleedHdConfig, patient) {
		this.$scope = $scope;
		this.PatientData = PatientData;
		this.BleedHdConfig = BleedHdConfig;
		this.patient = patient;

		this.resetAssessmentFilter();
		this.currentStatus = null;
		// this is necessary due to the way the ui.bootstrap handles the tab active state
		this.tabs = {
			assessments: ($routeParams.tab === 'assessments'),
			status: ($routeParams.tab === 'status'),
			remarks: ($routeParams.tab === 'remarks'),
		}

		var that = this;
		angular.forEach(that.patient.statuses, function (status) {
			if (that.currentStatus === null || that.currentStatus.transplant_date < status.transplant_date) {
				that.currentStatus = status;
			}
		});
	}

	bleedHd.registerController('patient', PatientDetailController,
		{
			byAssessmentType: function () {
				var ctl = this;

				return function (assessment, index) {
					var type = ctl.getAssessmentType(assessment);
					return ctl.assessmentFilter[type];
				};
			},
			getAssessmentType: function(assessment) {
				if (assessment.questionnaire === 'who') {
					return 'who';
				} else if (assessment.questionnaire === 'bsms') {
					return 'bsms';
				} else {
					return 'gvhd';
				}
			},
			resetAssessmentFilter: function () {
				this.assessmentFilter = {
					who: true,
					bsms: true,
					gvhd: true,
				};
			},
			supportsAssessmentGroup: function (group) {
				return angular.isArray(this.BleedHdConfig.allowed_assessment_types[group]) && this.BleedHdConfig.allowed_assessment_types[group].length > 0;
			},
		},
		{
			asName: 'ctlPatient',
			templateUrl: bleedHd.getView('patient', 'detail'),
			resolve: {
				patient: function ($route, PatientData) { return PatientData.getPatient($route.current.params.patientId); },
			},
		}
	);

})(angular, bleedHd);
