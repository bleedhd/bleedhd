
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
		},
		delayMap = {
			'normal': 'normal',
			'lateonset': 'late-onset',
		};

	angular.module('assessment')

		.filter('score', function ($filter) {
			return function (assessment) {
				if (assessment === undefined || assessment.result === undefined || assessment.result.score === undefined || assessment.result.score.total === undefined) {
					return 'pending';
				}

				switch (assessment.questionnaire) {
					case 'agvhd-follow-up':
						return $filter('newDiagnosisScoreAcute')(assessment.result.score);
					default:
						return assessment.result.score.total;
				}
			};
		})

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
				if (score === undefined || score.chronic === undefined || score.chronic === null)
					return '-';

				return chronicMap[score.chronic] || score.chronic;
			};
		})

		.filter('newDiagnosisScoreAcute', function () {
			return function (score) {
				if (score === undefined || score.acute === undefined || score.acute === null)
					return '-';

				return (isNaN(score.acute) ? score.acute : acuteMap[score.acute]);
			};
		})

		.filter('newDiagnosisScoreDelay', function () {
			return function (score) {
				if (score === undefined || score.acuteDelay === undefined || score.acuteDelay === null)
					return '-';

				return delayMap[score.acuteDelay] || score.acuteDelay;
			};
		})

		; // finally end the giant statement

})(angular);
