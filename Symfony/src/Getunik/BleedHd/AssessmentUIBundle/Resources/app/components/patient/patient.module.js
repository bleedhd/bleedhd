
(function (angular, bleedHd) {

	var module = angular.module('patient', [
		bleedHd.getView('patient', 'overview'),
	]);

})(angular, bleedHd);
