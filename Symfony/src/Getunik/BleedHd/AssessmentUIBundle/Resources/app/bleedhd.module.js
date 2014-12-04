
(function (angular) {

	window.bleedHd = {};
	var bleedHd = window.bleedHd;

	bleedHd.controllers = {};
	bleedHd.registerController = function (module, controller, name) {
		name = name || controller.name;
		angular.module(module).controller(name, controller);

		bleedHd.controllers[name] = {
			controller: name,
			controllerAs: controller.asName,
			templateUrl: controller.defaultTemplate,
			resolve: controller.resolve,
		};
	}

	bleedHd.basePath = 'GetunikBleedHdAssessmentUIBundle/Resources/app';
	bleedHd.getView = function (component, view) {
		return [bleedHd.basePath, 'components', component, view + '.view.html'].join('/');
	};

	bleedHd.app = angular.module('bleedHdApp', [
		'ngRoute',
		'ngResource',
		'ui.router',
		'patient',
	])

	.constant('bleedHdConfig', {
		version: '1.0.0',
		format: {
			birthdate: 'dd.MM.yyyy',
			yesno: ['yes', 'no'],
		},
	})

	;

})(angular);
