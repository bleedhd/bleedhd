
(function (angular) {

	window.bleedHd = {};
	var bleedHd = window.bleedHd;

	bleedHd.basePath = 'GetunikBleedHdAssessmentUIBundle/Resources/app';
	bleedHd.getView = function (component, view) {
		return [bleedHd.basePath, 'components', component, view + '.view.html'].join('/');
	};

	console.log("one");

	bleedHd.app = angular.module('bleedHdApp', [
		'ngRoute',
		'ngResource',
		'ui.router',
		'patient',
	]);

})(angular);
