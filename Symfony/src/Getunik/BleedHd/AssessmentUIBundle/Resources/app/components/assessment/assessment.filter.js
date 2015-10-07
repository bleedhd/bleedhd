
(function (angular) {

	var chronicMap = {
			'positive': 'positive',
			'pending': 'pending',
			'negative': 'negative',
		},
		delayMap = {
			'normal': 'normal',
			'lateonset': 'late-onset',
		};

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

		.filter('firstDiagnosisScoreChronic', function () {
			return function (score) {
				if (score === undefined || score === null)
					return '-';

				return chronicMap[score] || score;
			};
		})

		.filter('firstDiagnosisScoreAcute', function () {
			return function (score) {
				if (score === undefined || score === null)
					return '-';

				return (isNaN(score) ? score : 'Grade ' + score);
			};
		})

		.filter('firstDiagnosisScoreDelay', function () {
			return function (score) {
				if (score === undefined || score === null)
					return '-';

				return delayMap[score] || score;
			};
		})

		; // finally end the giant statement

})(angular);
