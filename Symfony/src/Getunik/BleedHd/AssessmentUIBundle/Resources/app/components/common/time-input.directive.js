
(function (angular) {

	angular.module('common')

		.directive('timeInput', function (DateHelper) {
			return {
				require: 'ngModel',
				restrict: 'A',
				scope: true,
				link: function (scope, element, attrs, modelCtrl) {
					element.inputmask('hh:mm', {
						showMaskOnHover: false,
						clearIncomplete: true,
						// some weird handling of the last change to the mask (which clears it)
						// causes that change to not propagate properly; as a workaround we just
						// set the view value manually on clear
						oncleared: function () {
							modelCtrl.$setViewValue('');
						},
					});

					modelCtrl.$parsers.push(function (value) {
						if (element.inputmask('unmaskedvalue') === '') {
							return null;
						}

						if (element.inputmask('isComplete')) {
							return DateHelper.fromTimeString(value);
						}
					});

					modelCtrl.$formatters.push(function (value) {
						if (value) {
							return value.format('HH:mm');
						}
					});
				},
			};
		})

	;

})(angular);
