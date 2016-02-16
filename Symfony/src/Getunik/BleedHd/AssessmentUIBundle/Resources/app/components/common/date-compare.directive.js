
(function (angular, bleedHd) {

	angular.module('common')
		.directive('dateCompare', function() {
			return {
				restrict: 'A',
				require: '?ngModel',
				scope: true,
				link: function(scope, elm, attrs, ctrl) {
					if (!ctrl) return;

					var expression = attrs.dateCompare;

					scope.now = new Date();
					scope.current = ctrl.$modelValue.date;

					scope.$watch(expression, function (newValue, oldValue) {
						ctrl.$validate();
					});

					ctrl.$validators.dateCompare = function(modelValue, viewValue) {

						if (ctrl.$isEmpty(modelValue)) {
							// consider empty models to be valid
							return true;
						}

						scope.current = modelValue.date;

						try {
							var result = scope.$eval(expression, {
								now: new Date(),
							});
						} catch (e) {
							return false;
						}

						return result;
					};
				},
			};
		});

})(angular, bleedHd);
