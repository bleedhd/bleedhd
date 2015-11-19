
(function (angular) {

	var chronicMap = {
			'positive': 'positive',
			'pending': 'pending',
			'negative': 'negative',
		},
		acuteMap = {
			'0': 'no aGVHD',
			'1': 'Grade I',
			'2': 'Grade II',
			'3': 'Grade III',
			'4': 'Grade IV',
		}
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

		.filter('newDiagnosisScoreChronic', function () {
			return function (score) {
				if (score === undefined || score === null)
					return '-';

				return chronicMap[score] || score;
			};
		})

		.filter('newDiagnosisScoreAcute', function () {
			return function (score) {
				if (score === undefined || score === null)
					return '-';

				return (isNaN(score) ? score : acuteMap[score]);
			};
		})

		.filter('newDiagnosisScoreDelay', function () {
			return function (score) {
				if (score === undefined || score === null)
					return '-';

				return delayMap[score] || score;
			};
		})

		; // finally end the giant statement

})(angular);
