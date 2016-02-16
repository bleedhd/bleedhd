
(function (angular) {

	window.bleedHd = {};
	var bleedHd = window.bleedHd;

	bleedHd.controllers = {};
	bleedHd.env = angular.extend({
		debug: false,
		environment: 'prod',
		// the -2 signifies that the UID has not yet been retrieved from the server (unauthenticated)
		// while a value of -1 would indicate an unauthenticated user
		uid: -2,
	}, window.env);

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
		'ngSanitize',
		'ui.router',
		'restangular',
		'authHandler',
		'typeRegistry',
		'eventChannel',
		'enhancedLog',
		'common',
		'pages',
		'patient',
		'assessment',
		'question',
	])

	.constant('BleedHdConfig', {
		login: '/user/login',
		logout: '/user/logout',
		redirectParam: '_target_path',
		messages: {
			hideDelay: 5000,
		},
		api: {
			base: '/api',
		},
		format: {
			isodate: 'yyyy-MM-dd',
			yesno: ['yes', 'no'],
		},
		resourcesPath: bleedHd.env.assetPath + '/getunikbleedhdassessmentui',
		assessmentResourcesPath: bleedHd.env.assetPath + '/getunikbleedhdassessmentdata',
	})

	.constant('DomainConst', {
		progress: {
			completed: 'completed',
			tentative: 'tentative',
			none: 'none',
		},
	})

	.config(function ($provide, $httpProvider, CachingWrapperProvider, EnhancedLogConfigProvider, AuthHandlerProvider, LoginRedirectProvider) {
		$httpProvider.interceptors.push('JsonDateInterceptor');

		AuthHandlerProvider.addExpirationCallback(function () {
			// we have to work with the provider here, but we know the callback and therefore
			// the $get function will only be called after proper initialization
			LoginRedirectProvider.$get()(true);
		});

		// extend the (customized) Restangular service implementation to wait for
		// the Authorization Handler promise to resolve before executing any HTTP request
		$provide.decorator('RestangularResource', function ($delegate, $location, $log, AuthHandler, LoginRedirect) {
			var oldExecuteRequest = $delegate.executeRequest;

			// always wait for authorization before executing the request
			$delegate.executeRequest = function (params) {
				return AuthHandler.authorized(function () {
					return oldExecuteRequest(params);
				}, function (error) {
					$log.warn('AuthHandler refused, redirecting to login...', error);
					LoginRedirect();
					throw error;
				});
			};

			return $delegate;
		});

		$provide.decorator('$exceptionHandler', function ($delegate, MessageBuilder) {
			return function (exception, cause) {
				MessageBuilder.send('unhandledException', [exception, cause]);
				return $delegate(exception, cause);
			};
		});

		// sets default caching lifetime to 10 min
		CachingWrapperProvider.setDefaultLifetime(10 * 60);

		EnhancedLogConfigProvider
			.setLogLevel(EnhancedLogConfigProvider.DEBUG)
			.setStoreLogLevel(EnhancedLogConfigProvider.INFO)
			.setUidFunc(function () { return bleedHd.env.uid; });
	})

	.run(function ($rootScope, $interval, $http, $log, ServerLogDump, HeaderControl, AuthHandler, ClaimsHandler) {
		$rootScope.env = bleedHd.env;
		$rootScope.header = HeaderControl;

		// do the log dumping now (on load) and every 15min
		ServerLogDump.start(15);

		$rootScope.$on('$routeChangeSuccess', function () {
			// navigating counts as a noteworthy activity in the context of
			// auto-logout behavior
			AuthHandler.updateLastActivity();
		});

		$rootScope.claims = ClaimsHandler;

		// ping the server in a 5 minute interval to keep the user's PHP session alive
		$interval(function () {
			$http.get('/user/ping').error(function(msg, code) {
				$log.info('error pinging server', msg, code);
			});
		}, 5 * 60 * 1000);
	})

	;

})(angular);
