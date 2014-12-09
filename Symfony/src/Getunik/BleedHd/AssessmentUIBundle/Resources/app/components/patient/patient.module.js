
(function (angular, bleedHd) {

	var module = angular.module('patient', [
		//'bleedHdApp',
		bleedHd.getView('patient', 'overview'),
		bleedHd.getView('patient', 'detail'),
		bleedHd.getView('patient', 'edit'),
		bleedHd.getView('patient', 'status-edit'),
	]);

})(angular, bleedHd);
