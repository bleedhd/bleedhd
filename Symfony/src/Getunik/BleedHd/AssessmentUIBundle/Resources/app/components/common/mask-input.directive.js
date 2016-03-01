
(function (angular) {

	var formats = {
		'hh:mm': {
			parse: function (DateHelper, value) {
				return DateHelper.fromTimeString(value);
			},
			display: function (DateHelper, value) {
				return value.formatInput('HH:mm:ss.SSS');
			},
		},
		'yyyy-mm-dd': {
			parse: function (DateHelper, value) {
				return DateHelper.fromString(value);
			},
			display: function (DateHelper, value) {
				return value.formatInput('YYYY-MM-DD');
			},
		},
	};

	angular.module('common')

		.directive('maskInput', function (DateHelper) {
			return {
				require: 'ngModel',
				restrict: 'A',
				link: function (scope, element, attributes, modelCtrl) {
					var format = formats[attributes.maskInput];
					if (format === undefined) {
						throw new Error('unknown mask input format "' + attributes.maskInput + '"');
					}

					element.inputmask(attributes.maskInput, {
						showMaskOnHover: false,
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
							return format.parse(DateHelper, value);
						}
					});

					modelCtrl.$formatters.push(function (value) {
						if (value) {
							return format.display(DateHelper, value);
						}
					});
				},
			};
		})

	;

})(angular);
