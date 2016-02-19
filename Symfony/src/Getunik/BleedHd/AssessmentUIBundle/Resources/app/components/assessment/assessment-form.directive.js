
(function (angular, bleedHd) {

	function AssessmentFormController($scope, $element, $attrs, $transclude, $timeout, BleedHdConfig, AssessmentData, DomainConst, FormWrapper, DateHelper) {
		this.$scope = $scope;
		this.$timeout = $timeout;
		this.BleedHdConfig = BleedHdConfig;
		this.AssessmentData = AssessmentData;
		this.DomainConst = DomainConst;

		this.assessment = FormWrapper($scope.assessment);

		// this is necessary for the FormWrapper to set up a 'copy' of the start_date property
		// since setDate and setTime would otherwise operate on the original value
		this.assessment.start_date = DateHelper.fromDate(this.assessment.start_date.date, true);
		this.startDate = DateHelper.fromDate(this.assessment.start_date.date, false);
		this.startTime = DateHelper.fromDate(this.assessment.start_date.date, true);

		this.isNew = (this.assessment.id === undefined);
	}

	angular.extend(AssessmentFormController.prototype, {
		onDateChange: function () {
			this.assessment.start_date.setDate(this.startDate === undefined ? undefined : this.startDate.date);
		},
		onTimeChange: function () {
			this.assessment.start_date.setTime(this.startTime === undefined ? undefined : this.startTime.date);
		},
		save: function () {
			var ctl = this;
			if (ctl.assessmentForm.$valid) {
				ctl.AssessmentData.saveAssessment(ctl.assessment.persist()).then(function () {
					ctl.successMessage = 'Metadata saved';
					ctl.$timeout(function () {
						ctl.successMessage = null;
					}, ctl.BleedHdConfig.messages.hideDelay);
				});
			}
		},
	});

	angular.module('assessment')
		.directive('assessmentForm', function() {
			return {
				restrict: 'E',
				scope: {
					assessment: '=',
				},
				templateUrl: bleedHd.getView('assessment', 'assessment-form'),
				controllerAs: 'assessmentFormCtl',
				controller: AssessmentFormController,
			};
		});

})(angular, bleedHd);
