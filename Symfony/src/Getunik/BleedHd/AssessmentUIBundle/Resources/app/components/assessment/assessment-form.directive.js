
(function (angular, bleedHd) {

	function AssessmentFormController($scope, $element, $attrs, $transclude, $timeout, $q, BleedHdConfig, AssessmentData, DomainConst, FormWrapper, DateHelper) {
		this.$scope = $scope;
		this.$timeout = $timeout;
		this.$q = $q;
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

		$scope.register({ formController: this });
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

			ctl.trySave().then(function (assessment) {
				if (assessment !== null) {
					ctl.successMessage = 'Metadata saved';
					ctl.$timeout(function () {
						ctl.successMessage = null;
					}, ctl.BleedHdConfig.messages.hideDelay);
				}
			});
		},
		trySave: function () {
			var ctl = this;
			if (ctl.assessmentForm.$valid) {
				return ctl.AssessmentData.saveAssessment(ctl.assessment.persist());
			}
			return ctl.$q.when(null);
		}
	});

	angular.module('assessment')
		.directive('assessmentForm', function() {
			return {
				restrict: 'E',
				scope: {
					assessment: '=',
					register: '&',
				},
				templateUrl: bleedHd.getView('assessment', 'assessment-form'),
				controllerAs: 'assessmentFormCtl',
				controller: AssessmentFormController,
			};
		});

})(angular, bleedHd);
