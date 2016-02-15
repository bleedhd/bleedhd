
(function (angular, bleedHd) {

	var compareExp = /([<>=])\s*(.*)/;

	angular.module('common')
		.directive('dateCompare', function() {
			return {
				restrict: 'A',
				require: '?ngModel',
				link: function(scope, elm, attrs, ctrl) {
					if (!ctrl) return;

					var op = '', other = null;

					attrs.$observe('dateCompare', function (value) {
						op = '';
						other = null;
						match = compareExp.exec(value);

						if (match) {
							op = match[1];

							if (match[2] === 'now') {
								other = new Date();
							} else {
								var date = Date.parse(match[2]);
								if (!isNaN(date)) {
									other = new Date(date);
								}
							}
						}

						ctrl.$validate();
					});

					ctrl.$validators.dateCompare = function(modelValue, viewValue) {

						if (ctrl.$isEmpty(modelValue) || other === null) {
							// consider empty models to be valid
							return true;
						}

						switch (op) {
							case '<':
								return modelValue.date < other;

							case '>':
								return modelValue.date > other;

							case '=':
								return modelValue.date = other;
						}

						return true;
					};
				},
			};
		});

})(angular, bleedHd);
