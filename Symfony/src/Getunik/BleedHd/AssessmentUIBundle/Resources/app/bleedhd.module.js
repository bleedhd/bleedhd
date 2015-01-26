
(function (angular) {

	window.bleedHd = {};
	var bleedHd = window.bleedHd;

	bleedHd.controllers = {};

	/**
	 * Registers a controller with the bleedHd helper so that it can be used to conveniently specify routes like so:
	 * @example Example of a route definition with the global bleedHd helper
	 * .when('/patients', bleedHd.controllers.PatientOverviewController)
	 *
	 * This approach to controllers and routes provides the following advantages over "standard" AngularJS sample code:
	 * - the constructor function and the controller's methods are defined in one operation
	 * - the controller function does not have to live in the global scope; the entire registration can (and should) be done in a closure
	 * - the constructor can provide default values for all route parameters
	 * - it is still possible (but not necessary) to override the default route parameters of a controller (e.g. use a different view)
	 *
	 * @param {string} module - parent module name to register the controller
	 * @param {function} constructor - the constructor function for the controller
	 * @param {object} methods - arbitrary object that will be merged with the controller's prototype to provide controller methods
	 * @param {object} routeDefaults - default route parameters that should be used with this constructor (@see https://docs.angularjs.org/api/ngRoute/provider/$routeProvider)
	 *   @param {string} routeDefaults.controllerAs - name under which the controller will be available in the template
	 *   @param {string|function} routeDefaults.template - template markup or function that returns template markup
	 *   @param {string|function} routeDefaults.templateUrl - the path of a template or a function that returns the path of a template
	 *   @param {object} routeDefaults.resolve - an optional map of dependencies which should be injected into the controller
	 *
	 * Note that you should specify at least _one_ of the 'template' or 'templateUrl' properties
	 */
	bleedHd.registerController = function (module, constructor, methods, routeDefaults) {
		// add the controller methods to the controller's prototype
		angular.extend(constructor.prototype, methods);
		angular.module(module).controller(constructor.name, constructor);

		bleedHd.controllers[constructor.name] = {
			controller: constructor,
			controllerAs: routeDefaults.asName,
			template: routeDefaults.template,
			templateUrl: routeDefaults.templateUrl,
			resolve: routeDefaults.resolve,
		};
	};

	bleedHd.basePath = 'GetunikBleedHdAssessmentUIBundle/Resources/app';

	/**
	 * This function gets the "virtual view path" (aka the key in the template cache) of a
	 * precompiled view.
	 *
	 * @param {string} component - name of the component the view belongs to (component path segment)
	 * @param {string} view - base name of the template (part before ".view.html")
	 */
	bleedHd.getView = function (component, view) {
		return [bleedHd.basePath, 'components', component, view + '.view.html'].join('/');
	};

	bleedHd.app = angular.module('bleedHdApp', [
		'ngRoute',
		'ngResource',
		'ngSanitize',
		'ui.router',
		'restangular',
		'typeRegistry',
		'patient',
		'assessment',
		'question',
	])

	.constant('BleedHdConfig', {
		version: '1.0.0',
		api: {
			host: '',
			base: '/api',
			resources: {
				patients: 'patients',
				statuses: 'patients/:patientId/statuses',
			},
		},
		format: {
			isodate: 'yyyy-MM-dd',
			yesno: ['yes', 'no'],
		},
	})

	.config(function ($provide, $httpProvider) {
		$httpProvider.interceptors.push('JsonDateInterceptor');

		// extend the (customized) Restangular service implementation to wait for
		// the Authorization Handler promise to resolve before executing any HTTP request
		$provide.decorator('RestangularResource', function ($delegate, AuthHandler) {
			var oldExecuteRequest = $delegate.executeRequest;

			// always wait for authorization before executing the request
			$delegate.executeRequest = function (params) {
				return AuthHandler.authorized(function () {
					return oldExecuteRequest(params);
				});
			};

			return $delegate;
		});
	})

	.run(function ($rootScope) {
		$rootScope.env = angular.extend({}, window.env, {
			debug: location.search.match(/debug=true(&|$)/) !== null,
		});
	})

	;

})(angular);
