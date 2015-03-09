
(function (angular) {

	function AuthHandlerProvider() {
		// 3.3min check interval (not a factor of 15)
		this.checkInterval = 3.3 * 60 * 1000;
		// 15min activity interval
		this.activityWindow = 15 * 60 * 1000;
		this.expirationCallbacks = [];
	}

	angular.extend(AuthHandlerProvider.prototype, {
		$get: function ($http, $log, $q, $interval) {
			return new AuthHandler($http, $log, $q, $interval, this);
		},
		setCheckInterval: function (interval) {
			this.checkInterval = interval;
		},
		setActivityWindow: function (win) {
			this.activityWindow = win;
		},
		addExpirationCallback: function (callback) {
			this.expirationCallbacks.push(callback);
		},
		triggerExpirationCallbacks: function () {
			angular.forEach(this.expirationCallbacks, function (callback) {
				callback();
			});
		},
	});


	/**
	 * The AuthHandler manages an OAuth token and provides functionality to integrate the token
	 * into webservice requests. @see BleedApi service for more information.
	 */
	function AuthHandler($http, $log, $q, $interval, config) {
		var that = this;

		that.$http = $http;
		that.$log = $log;
		that.$q = $q;

		that.$log.debug('config', config);
		that.config = config;
		that.lastActivity = Date.now();
		that.deferred = $q.defer();
		that.token = {
			value: null,
			toString: function () { return (this.value === null ? 'token not yet available' : 'Bearer ' + this.value); },
		};

		that.$http.get('/user/gettoken').
			success(function(data, status, headers, config) {
				that._processTokenResponse(data);
				that.$log.debug('got the token', that.authInfo);
				$interval(that.checkToken.bind(that), that.config.checkInterval);
				that.deferred.resolve(data);
				that.checkToken();
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
		authorized: function (callback, errorCallback) {
			this.updateLastActivity();
			return this.deferred.promise.then(function (data) {
				return callback(data);
			}, errorCallback);
		},
		/**
		 * Updates the internal last activity timestamp that is used to determine if the user has been active within
		 * the activityWindow. Calling the authorized function automatically does this already, but if your application
		 * has other useful ways of determining user activity, this function can be called manually.
		 */
		updateLastActivity: function () {
			this.lastActivity = Date.now();
		},
		/**
		 * Periodically checks the token expiration and refreshes the token if
		 * - it would expire _before_ the next interval
		 * - AND there was activity[*] within the config.activityWindow period
		 *
		 * [*] any call to the authorized function counts as activity
		 */
		checkToken: function () {
			var that = this;

			that.$log.debug('checking token expiration', that.authInfo.expires_at.date, new Date(that.lastActivity));

			if (angular.isDefined(that.authInfo) && that.authInfo.expires_at.date.getTime() < Date.now() + that.config.checkInterval) {
				if (Date.now() < that.lastActivity + that.config.activityWindow) {
					that.$log.debug('refreshing token');

					that.deferred = that.$q.defer();
					that.$http.get('/user/refreshtoken').
						success(function(data, status, headers, config) {
							that._processTokenResponse(data);
							that.$log.debug('renewed the token', that.authInfo);
							that.deferred.resolve(data);
						}).error(function(msg, code) {
							that.deferred.reject(msg);
						});
				} else {
					that.$log.info('letting the token expire due to inactivity', new Date(that.lastActivity));
					delete that.authInfo;
					that.token.value = null;
					that.deferred = that.$q.defer();
					that.deferred.reject('inactivity');
					that.config.triggerExpirationCallbacks();
				}
			}
		},
		_processTokenResponse: function (data) {
			if (data.access_token === undefined || data.expires_at.date < new Date()) {
				this.config.triggerExpirationCallbacks();
				return;
			}
			this.authInfo = data;
			this.token.value = data.access_token;
			bleedHd.env.uid = data.uid;
		},
	});


	angular.module('authHandler', [])

		/**
		 * The AuthHandler service provides a central point to get the current user's authentication
		 * token. It _is_ a promise ($q deferred) that resolves as soon as the current token is available
		 * and passes the token as the resolution argument. See the secureResource service on how it
		 * can be used.
		 */
		.provider('AuthHandler', AuthHandlerProvider)

	;

})(angular);
