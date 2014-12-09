
(function (angular, bleedHd) {

	function PatientOverviewController($scope, $filter, patients) {
		this.patients = patients;
		this.birthdateFilter = $filter('birthdate');
		this.resetFilter();
	}

	angular.extend(PatientOverviewController.prototype, {
		filterPatientsFulltext: function (pattern) {
			var ctl = this;

			// build a custom 'full text' search string from the patient properties and
			// match it against a regular expression created from the given pattern value
			return function (value, index) {
				var fullText = [value.firstname, value.lastname, ctl.birthdateFilter(value.birthdate)].join('|'),
					search = new RegExp(pattern, 'i');

				return fullText.match(search) !== null;
			};
		},
		resetFilter: function () {
			this.filterActive = true;
			this.filterSearch = '';
		},
	});


	PatientOverviewController.asName = 'ctlPatients';
	PatientOverviewController.defaultTemplate = bleedHd.getView('patient', 'overview');
	PatientOverviewController.resolve = {
		patients: function (patientData) { return patientData.getPatients(); },
	};

	bleedHd.registerController('patient', PatientOverviewController);

})(angular, bleedHd);
