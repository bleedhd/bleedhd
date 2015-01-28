
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
			toString: function () { return (this.value === null ? 'token not yet available' : 'Bearer ' + this.value); },
		};

		$http.get('/user/gettoken').
			success(function(data, status, headers, config) {
				console.log("got the token", data);
				that.deferred.resolve(data);
			}).error(function(msg, code) {
				that.deferred.reject(msg);
			});

		// this is dummy code useful for testing without security
		/*window.setTimeout(function () {
			that.deferred.resolve({
				access_token: 'qwer',
				expires_at: new Date(),
				refresh_token: 'asdf',
			});
		}, 500);*/

		this.deferred.promise.then(function (data) {
			that.token.value = data.access_token;
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


	function DateHelper(dateFilter, date, match) {
		this.dateFilter = dateFilter;
		if (date === undefined || angular.isDate(date)) {
			this.date = date;
			this.isDateTime = true;
		} else {
			this.date = new Date(Date.parse(date));
			this.isDateTime = !!match[1];
		}
	}

	angular.extend(DateHelper.prototype, {
		toJSON: function () {
			if (!angular.isDate(this.date))
			{
				return this.date;
			}
			return this.isDateTime ? this.dateFilter(this.date, 'yyyy-MM-ddTHH:mm:ss.sssZ') : this.dateFilter(this.date, 'yyyy-MM-dd');
		},
		toString: function () {
			return (this.isDateTime ? 'DateTime(' : 'Date(') + this.toJSON() + ')';
		},
		setTime: function (time) {
			if (time !== undefined && time !== null) {
				this.date.setHours(time.getHours());
				this.date.setMinutes(time.getMinutes());
				this.date.setSeconds(time.getSeconds());
				this.date.setMilliseconds(time.getMilliseconds());
			}
		},
		setDate: function (date) {
			if (time !== undefined && time !== null) {
				this.date.setYear(date.getFullYear());
				this.date.setMonth(date.getMonth());
				this.date.setDate(date.getDate());
			}
		},
	});

	function DateHelperService($filter) {
		this.dateFilter = $filter('date');
	}

	angular.extend(DateHelperService.prototype, {
		iso8601RegEx: /^\d{4}-\d{2}-\d{2}(T\d{2}:\d{2}:\d{2}(\.\d+)?([+-]\d{4}|Z))?$/,
		fromString: function (str) {
			if (typeof(str) === 'string' && (match = str.match(this.iso8601RegEx))) {
				return new DateHelper(this.dateFilter, str, match);
			}
		},
		fromDate: function (date, isDateTime) {
			var helper = new DateHelper(this.dateFilter, date);
			helper.isDateTime = isDateTime;
			return helper;
		}
	});


	angular.module('bleedHdApp')

		/**
		 * The AuthHandler service provides a central point to get the current user's authentication
		 * token. It _is_ a promise ($q deferred) that resolves as soon as the current token is available
		 * and passes the token as the resolution argument. See the secureResource service on how it
		 * can be used.
		 */
		.service('AuthHandler', AuthHandler)

		.service('DateHelper', DateHelperService)

		.service('DataEvents', function (EventChannelFactory) {
			return EventChannelFactory('DataEvents');
		})

		/**
		 * The JSON date interceptor is an HTTP interceptor implementation that transforms properties
		 * with values that match an ISO-8601 date-time into a JavaScript Date object. It is added to
		 * the default HTTP interceptors by this module's basic configuration.
		 */
		.factory('JsonDateInterceptor', function(DateHelper) {
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
				var date = DateHelper.fromString(value);

				if (date !== undefined) {
					return date;
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
		.factory('BleedApi', function (Restangular, AuthHandler, $window) {
			return Restangular.withConfig(function(RestangularConfig) {
				RestangularConfig
					.setBaseUrl('/api')
					.setDefaultHeaders({ 'Authorization': AuthHandler.getToken() })
					.setErrorInterceptor(function (response) {
						console.log("resource request error", response);
						if (response.status === 403 || response.status === 401) {
							console.log("Login required. Redirecting...");
							$window.location.href='/user/login';
						}

						return false;
					});
			});
		})

		; // finally end the giant statement

})(angular, bleedHd);
