
(function (angular, bleedHd) {

	function PatientDetailController($scope, PatientData, patient) {
		this.$scope = $scope;
		this.PatientData = PatientData;
		this.patient = patient;

		this.resetAssessmentFilter();
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
