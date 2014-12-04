
(function (angular, bleedHd) {

	var module = angular.module('patient', [
		bleedHd.getView('patient', 'overview'),
		bleedHd.getView('patient', 'edit'),
	]);

})(angular, bleedHd);
