
(function (angular) {

	function xor (a, b) { return ( a || b ) && !( a && b );	}

	angular.module('question').directive('modelFilterExpression', function () {
		return {
			require: 'ngModel',
			restrict: 'A',
			scope: {
				filterExpression: '&modelFilterExpression',
				invert: '&',
			},
			link: function (scope, element, attrs, modelCtrl) {
				var filterExpression = scope.filterExpression(),
					invert = scope.invert() || false;

				if (!!filterExpression) {
					modelCtrl.$parsers.push(function (value) {

						if (value !== null && xor(value.match(filterExpression) === null, invert) ) {
							// reset to old value
							value = modelCtrl.$modelValue;
							modelCtrl.$setViewValue(value);
							modelCtrl.$render();
						}

						return value;
					});
				}
			},
		};
	});

})(angular);
