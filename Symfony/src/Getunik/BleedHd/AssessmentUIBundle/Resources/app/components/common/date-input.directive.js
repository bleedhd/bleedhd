
(function (angular) {

	angular.module('common')

		.directive('dateInput', function (DateHelper) {
			return {
				require: 'ngModel',
				restrict: 'A',
				scope: true,
				link: function (scope, element, attrs, modelCtrl) {
					element.inputmask('yyyy-mm-dd', {
						clearIncomplete: true,
						// some weird handling of the last change to the mask (which clears it)
						// causes that change to not propagate properly; as a workaround we just
						// set the view value manually on clear
						oncleared: function () {
							console.log('oncleared');
							modelCtrl.$setViewValue('');
						},
					});

					modelCtrl.$parsers.push(function (value) {
						if (element.inputmask('unmaskedvalue') === '') {
							return null;
						}

						if (element.inputmask('isComplete')) {
							return DateHelper.fromString(value);
						}
					});

					modelCtrl.$formatters.push(function (value) {
						if (value) {
							return value.toJSON();
						}
					});
				},
			};
		})

	;

})(angular);
