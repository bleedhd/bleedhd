
(function (angular, bleedHd) {

	var module = angular.module('assessment', [
		//'bleedHdApp',
		'patient',
		bleedHd.getView('assessment', 'edit'),
	]);

})(angular, bleedHd);
