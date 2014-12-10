
(function (angular, bleedHd) {

	function PatientOverviewController($scope, $filter, patients) {
		this.patients = patients;
		this.birthdateFilter = $filter('birthdate');
		this.resetFilter();
	}

	bleedHd.registerController('patient', PatientOverviewController,
		{
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
		},
		{
			asName: 'ctlPatients',
			templateUrl: bleedHd.getView('patient', 'overview'),
			resolve: {
				patients: function (patientData) { return patientData.getPatients(); },
			},
		}
	);

})(angular, bleedHd);
