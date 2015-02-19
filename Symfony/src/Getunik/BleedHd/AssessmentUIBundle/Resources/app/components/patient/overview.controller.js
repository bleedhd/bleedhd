
(function (angular, bleedHd) {

	function PatientOverviewController($scope, $filter, $timeout, PatientData, patients) {
		this.PatientData = PatientData;
		this.$timeout = $timeout;
		this.patients = patients;
		this.patients.sort(function (a, b) { return a.lastname.toLowerCase().localeCompare(b.lastname.toLowerCase()); });

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

		this.asyncStatiPromise = null;
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
			// 		var fullText = [value.firstname, value.lastname, ctl.dateFilter(value.birthdate.date)].join('|'),
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
				this.paging.items = this.patients.filter(this.patientFilter.bind(this));
				this.processPageItems();
			},
			onPageChanged: function () {
				this.processPageItems();
			},
			patientFilter: function (patient) {
				if (this.filterActive && !patient.is_active) { return false; }

				var fullText = [patient.firstname, patient.lastname, this.dateFilter(patient.birthdate.date)].join('|'),
					search = new RegExp(this.filterSearch, 'i');

				return fullText.match(search) !== null;
			},
			processPageItems: function () {
				var that = this,
					start = (that.paging.currentPage - 1) * that.paging.itemsPerPage,
					end = start + that.paging.itemsPerPage,
					ids = [], patients = {}, index, item;

				for (index = start; index < end && index < that.paging.items.length; index++) {
					item = that.paging.items[index];
					if (item.status === undefined || item.status === null) {
						item.status = null;
						ids.push(item.id);
						patients[item.id] = item;
					}
				}

				if (ids.length > 0) {
					if (that.asyncStatiPromise !== null) {
						that.$timeout.cancel(that.asyncStatiPromise);
					}

					that.asyncStatiPromise = that.$timeout(function () {
						that.PatientData.getAssessmentStati(ids).then(function (stati) {
							console.log('stati', stati.length, stati);
							angular.forEach(stati, function (status) {
								patients[status.patient_id].status = status.complete;
							});
						});
					}, 300);
				}
			},
		},
		{
			asName: 'ctlPatients',
			templateUrl: bleedHd.getView('patient', 'overview'),
			resolve: {
				patients: function (PatientData) { return PatientData.getPatients(); },
			},
		}
	);

})(angular, bleedHd);
