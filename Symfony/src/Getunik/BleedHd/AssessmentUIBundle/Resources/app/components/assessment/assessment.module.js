
(function (angular, bleedHd) {

	var module = angular.module('assessment', [
		//'bleedHdApp',
		'patient',
		bleedHd.getView('assessment', 'edit'),
		bleedHd.getView('assessment', 'screen'),
	])

		.provider('ScoringRegistry', function (TypeRegistryFactoryProvider) {
			return TypeRegistryFactoryProvider.create('ScoringRegistry');
		})

	;

})(angular, bleedHd);
