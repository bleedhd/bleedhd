
(function (angular, bleedHd) {

	function PatientOverviewController($scope, $filter, patients) {
		this.patients = patients;
		this.patients.sort(function (a, b) { return a.lastname > b.lastname; });

		this.paging = {
			items: [],
			currentPage: 1,
			itemsPerPage: 30,
			getPageItems: function () {
				var start = (this.currentPage - 1) * this.itemsPerPage,
					end = start + this.itemsPerPage;

				return this.items.slice(start, end);
			},
		};
		$scope.paging = this.paging;

		this.dateFilter = $filter('isodate');
		this.resetFilter();
	}

	bleedHd.registerController('patient', PatientOverviewController,
		{
			// filterPatientsFulltext: function (pattern) {
			// 	var ctl = this;

			// 	// build a custom 'full text' search string from the patient properties and
			// 	// match it against a regular expression created from the given pattern value
			// 	return function (value, index) {
			// 		var fullText = [value.firstname, value.lastname, ctl.dateFilter(value.birthdate)].join('|'),
			// 			search = new RegExp(pattern, 'i');

			// 		return fullText.match(search) !== null;
			// 	};
			// },
			resetFilter: function () {
				this.filterActive = true;
				this.filterSearch = '';
				this.onFilterChange();
			},
			onFilterChange: function () {
				this.paging.currentPage = 1;
				this.paging.items = this.getPatientsPage();
			},
			getPatientsPage: function () {
				var start = (this.paging.currentPage - 1) * this.paging.itemsPerPage,
					end = start + this.paging.itemsPerPage;

				return this.patients.filter(this.patientFilter.bind(this));
			},
			patientFilter: function (patient) {
				if (this.filterActive && !patient.is_active) { return false; }

				var fullText = [patient.firstname, patient.lastname, this.dateFilter(patient.birthdate)].join('|'),
					search = new RegExp(this.filterSearch, 'i');

				return fullText.match(search) !== null;
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
