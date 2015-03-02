
(function (angular) {

	angular.module('assessment')

		.filter('organScore', function () {
			return function (score) {
				if (score === undefined || score === null)
					return '-';

				if (score.override !== undefined)
					return '0 (' + score.value + '*)';

				return score.value;
			};
		})

		; // finally end the giant statement

})(angular);
