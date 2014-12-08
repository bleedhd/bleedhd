
(function (angular, bleedHd) {

	angular.module('bleedHdApp')

		/**
		 * The authHandler service provides a central point to get the current user's authentication
		 * token. It _is_ a promise ($q deferred) that resolves as soon as the current token is available
		 * and passes the token as the resolution argument. See the secureResource service on how it
		 * can be used.
		 */
		.factory('authHandler', function($http, $q) {
			var authToken = $q.defer();

			authToken.resolve('abcdefg');

			// TODO: currently disabled for testing
			/*$http.get('/user/getToken').
				success(function(data, status, headers, config) {
					console.log("got the token", data);
					authToken.resolve(data);
				}).error(function(msg, code) {
					authToken.reject(msg);
				});*/

			return authToken.promise;
		})

		/**
		 * The secureResource service is basically a wrapper around the esisting $resource service that
		 * includes authorization using the 'Authorization' HTTP header. It responds to the same function
		 * calls as the $resource service and the returned resources do the same with one major difference
		 * in behavior: instead of returning incomplete objects, the actions called on the resources always
		 * return a promise that is only resolved when all the data has actually been fetched.
		 *
		 * @see https://docs.angularjs.org/api/ngResource/service/$resource under 'Returns'
		 */
		.factory('secureResource', function ($q, $resource, authHandler) {
			var defaultActions = {
				'get': {method: 'GET'},
				'save': {method: 'POST'},
				'query': {method: 'GET', isArray: true},
				'update': {method: 'PUT'},
				'delete': {method: 'DELETE'},
			};

			var SecureResource = function (url, paramDefaults, actions, options) {
				var promiseHandle = $q.defer();
				this.resourcePromise = promiseHandle.promise;

				var that = this;

				authHandler.then(function (authToken) {
					angular.forEach(actions, function(action, name) {
						if (action.headers === undefined) { action.headers = {}; }
						action.headers['Authorization'] = 'Bearer ' + authToken.access_token;
					});

					promiseHandle.resolve($resource(url, paramDefaults, actions, options));
				}, function (msg, code) {
					promiseHandle.reject(msg);
				});
			};

			SecureResource.prototype = {
				delegateTo: function (name, args) {
					var deferred = $q.defer(),
						result = deferred.promise;

					this.resourcePromise
						.then(function (resource) {
							return resource[name].apply(resource, args).$promise;
						})
						.then(function (result) {
							deferred.resolve(result);
						}, function (reason) {
							deferred.reject(reason);
						});

					return result;
				},
			};


			return function (url, paramDefaults, actions, options) {
				var actions = angular.extend({}, defaultActions, actions),
					result = new SecureResource(url, paramDefaults, actions, options);

				// create delegator functions for all actions
				angular.forEach(actions, function(action, name) {
					result[name] = function() {
						return this.delegateTo(name, Array.prototype.slice.call(arguments));
					};
				});

				return result;
			};

		})

		.factory('jsonDateInterceptor', function() {
			var iso8601RegEx = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\.\d+)?([+-]\d{4}|Z)$/;

			function recursiveProcess (data, processor) {
				angular.forEach(data, function(value, key, parent) {
					if (typeof(value) === 'object') {
						recursiveProcess(value, processor);
					} else {
						var processed = processor(key, value);
						if (processed !== undefined)
						{
							parent[key] = processed;
						}
					}
				});
			}

			function transformDateString(key, value) {
				if (typeof(value) === 'string' && value.match(iso8601RegEx)) {
					return new Date(Date.parse(value));
				}
			}

			return {
				response: function (response) {
					recursiveProcess(response.data, transformDateString);
					return response;
				},
			};
		})

		; // finally end the giant statement

})(angular, bleedHd);
