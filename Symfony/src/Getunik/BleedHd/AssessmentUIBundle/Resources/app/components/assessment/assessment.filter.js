
(function (angular) {

	angular.module('assessment')

		.filter('organScore', function () {
			return function (score) {
				if (score === undefined || score === null)
					return '-';

				return score.value;
			};
		})

		; // finally end the giant statement

})(angular);
