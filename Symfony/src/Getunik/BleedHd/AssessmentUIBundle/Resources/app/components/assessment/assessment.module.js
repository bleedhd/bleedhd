
(function (angular, bleedHd) {

	var module = angular.module('assessment', [
		'bleedHdApp',
		'typeRegistry',
		'patient',
	])

		.provider('ScoringRegistry', function (TypeRegistryFactoryProvider) {
			return TypeRegistryFactoryProvider.create('ScoringRegistry');
		})

	;

})(angular, bleedHd);
