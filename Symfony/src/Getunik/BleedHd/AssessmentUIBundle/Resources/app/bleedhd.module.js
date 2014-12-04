
(function (angular) {
	var viewModuleBase = 'GetunikBleedHdAssessmentUIBundle/Resources/app/components',
		viewModule = function (component, view) {
			return [viewModuleBase, component, view + '.view.html'].join('/');
		};

	console.log("one");

	angular.module('bleedHdApp', [
		'ngRoute',
		'ngResource',
		'ui.router',
		'patient',
		viewModule('patient', 'overview'),
	]);

})(angular);
