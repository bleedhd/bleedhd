
(function (angular, bleedHd) {

	var module = angular.module('assessment', [
		//'bleedHdApp',
		'patient',
		bleedHd.getView('assessment', 'edit'),
		bleedHd.getView('assessment', 'screen'),
	]);

})(angular, bleedHd);
