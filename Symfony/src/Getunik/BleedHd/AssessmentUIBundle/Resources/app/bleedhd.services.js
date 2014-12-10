
(function (angular, bleedHd) {

	/**
	 * The AuthHandler manages an OAuth token and provides functionality to integrate the token
	 * into webservice requests. @see BleedApi service for more information.
	 */
	function AuthHandler($http, $q) {
		var that = this;

		this.deferred = $q.defer();
		this.token = {
			value: null,
			toString: function () { return (this.value === null ? "token not yet available" : this.value); },
		};

		// TODO: currently disabled for testing
		/*$http.get('/user/getToken').
			success(function(data, status, headers, config) {
				console.log("got the token", data);
				authToken.resolve(data);
			}).error(function(msg, code) {
				authToken.reject(msg);
			});*/

		window.setTimeout(function () {
			that.deferred.resolve('qwer');
		}, 500);

		this.deferred.promise.then(function (data) {
			console.log("token resolved");
			that.token.value = data;
		});
	}

	angular.extend(AuthHandler.prototype, {
		/**
		 * The object returned by this function can be used as a proxy for the actual token since it is updated
		 * as soon as the token is available. As long as the serialization (conversion to string) happens after
		 * the token has been resolved, this will work; otherwise the token value will be reported as 'token not
		 * yet available'.
		 *
		 * @return {object} - a token object with a 'toString' function that simple returns the authentication token
		 */
		getToken: function () {
			return this.token;
		},
		/**
		 * Similar to the 'then' function on promises, this method will call the callback function with
		 * the token data object as soon as it is resolved.
		 *
		 * @param {function(object)} - callback that will be called whenever the authorization token has been resolved
		 */
		authorized: function (callback) {
			return this.deferred.promise.then(function (data) {
				return callback(data);
			});
		},
	});

	angular.module('bleedHdApp')

		/**
		 * The AuthHandler service provides a central point to get the current user's authentication
		 * token. It _is_ a promise ($q deferred) that resolves as soon as the current token is available
		 * and passes the token as the resolution argument. See the secureResource service on how it
		 * can be used.
		 */
		.service('AuthHandler', AuthHandler)

		/**
		 * The JSON date interceptor is an HTTP interceptor implementation that transforms properties
		 * with values that match an ISO-8601 date-time into a JavaScript Date object. It is added to
		 * the default HTTP interceptors by this module's basic configuration.
		 */
		.factory('JsonDateInterceptor', function() {
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

		/**
		 * The BleedApi service provides a convenient pre-configured Restangular object with
		 * integrated authorization.
		 */
		.factory('BleedApi', function (Restangular, AuthHandler) {
			return Restangular.withConfig(function(RestangularConfig) {
				RestangularConfig
					.setBaseUrl('/api')
					.setDefaultHeaders({ 'Authorization': AuthHandler.getToken() });
			});
		})

		; // finally end the giant statement

})(angular, bleedHd);
