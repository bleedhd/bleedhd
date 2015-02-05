
(function (angular, bleedHd) {

	function setErrorClass(attributes, input)
	{
		if (input.$dirty && input.$invalid) {
			attributes.$addClass('has-error');
		} else {
			attributes.$removeClass('has-error');
		}
	}

	angular.module('common')

		.directive('bsValidateClass', function ($rootScope) {
			return {
				restrict: 'A',
				scope: {
					input: '=bsValidateClass',
				},
				link: function (scope, element, attributes, controller, transcludeFn) {
					scope.$watch('input.$dirty', function (value) {
						setErrorClass(attributes, scope.input);
					});
					scope.$watch('input.$invalid', function (value) {
						setErrorClass(attributes, scope.input);
					});
				},
			};
		})

	;

})(angular, bleedHd);
