
(function (angular, bleedHd) {

	function DateHelper(dateFilter, date, match) {
		this.dateFilter = dateFilter;
		this.isDateTime = true;

		if (date !== undefined) {
			// this works with strings AND dates
			this.moment = moment(date);
		}
		if (this.moment !== undefined) {
			this.isDateTime = !(
				this.moment.hour() === 0 &&
				this.moment.minute() === 0 &&
				this.moment.second() === 0 &&
				this.moment.millisecond() === 0
			);
		}
	}

	angular.extend(DateHelper.prototype, {
		toJSON: function () {
			if (!angular.isDate(this.date)) {
				return angular.toJson(this.date);
			} else if (isNaN(this.date.getTime())) {
				return undefined;
			}

			return this.isDateTime ? this.dateFilter(this.date, 'yyyy-MM-ddTHH:mm:ss.sssZ') : this.dateFilter(this.date, 'yyyy-MM-dd');
		},
		toString: function () {
			return (this.isDateTime ? 'DateTime(' : 'Date(') + this.toJSON() + ')';
		},
		setTime: function (time) {
			if (time !== undefined && time !== null) {
				this.moment.hour(time.getHours());
				this.moment.minute(time.getMinutes());
				this.moment.second(time.getSeconds());
				this.moment.millisecond(time.getMilliseconds());
			}
		},
		setDate: function (date) {
			if (date !== undefined && date !== null) {
				this.moment.year(date.getFullYear());
				this.moment.month(date.getMonth());
				this.moment.date(date.getDate());
			}
		},
		format: function (format) {
			return this.moment === undefined ? 'Invalid Date' : this.moment.format(format);
		},
		formatInput: function (format) {
			return this.moment === undefined ? '' : this.moment.format(format);
		}
	});

	Object.defineProperty(DateHelper.prototype, 'date', {
		get: function () { return !!this.moment ? this.moment.toDate() : undefined; },
		set: function (val) { this.moment = (val === undefined ? undefined : moment(val)); },
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
		fromTimeString: function (str) {
			if (typeof(str) === 'string') {
				var helper = new DateHelper(this.dateFilter);
				helper.moment = moment(str, 'HH:mm:ss.SSS');
				helper.isDateTime = true;
				return helper;
			}
		},
		fromDate: function (date, isDateTime) {
			var helper = new DateHelper(this.dateFilter, date);
			helper.isDateTime = isDateTime;
			return helper;
		}
	});


	function HeaderControlService($rootScope) {
		var that = this;

		that.current = that.getDefaults();

		$rootScope.$on('$routeChangeStart', function (event, current, previous) {
			that.next = that.getDefaults();
		});

		$rootScope.$on('$routeChangeSuccess', function (event, current, previous) {
			that.current = that.next;
		});

		$rootScope.$on('$routeChangeError', function (event, current, previous) {
			that.current = that.next;
		});
	}

	angular.extend(HeaderControlService.prototype, {
		getDefaults: function () {
			return {
				show: true,
				allowLogout: true,
				altLink: null,
				altLabel: null,
			};
		},
		hide: function () {
			this.next.show = false;
		},
		disableLogout: function () {
			this.next.allowLogout = false;
		},
		enableAltLink: function (targetUrl, label) {
			this.next.altLink = targetUrl;
			this.next.altLabel = label;
			this.next.allowLogout = false;
		},
	});


	function ServerLogDump(BleedApi, LogData) {
		this.BleedApi = BleedApi;
		this.LogData = LogData;
	}

	angular.extend(ServerLogDump.prototype, {
		start: function (interval) {
			window.setInterval(this.dump.bind(this), interval * 60 * 1000);
			this.dump();
		},
		dump: function () {
			var entries = this.LogData.getAll(), response;

			if (entries.length > 0) {
				// make sure that an error in the stored log data doesn't permanently screw up
				// all future attempts of sending log data
				this.LogData.clear();
				response = this.BleedApi.all('logentries').customPOST(entries, 'batch');
				return response;
			}
		},
	});


	function LoginRedirect($window, BleedHdConfig) {
		return function (forceLogout) {
			if (forceLogout === true) {
				$window.location.href = BleedHdConfig.logout + '?' + BleedHdConfig.redirectParam + '=' + encodeURIComponent($window.location.href);
			} else {
				$window.location.href = BleedHdConfig.login + '?' + BleedHdConfig.redirectParam + '=' + encodeURIComponent($window.location.href);
			}
		};
	}


	function FormWrapperFactory() {
		return function (original) {
			// Creates a 'proxy' object that forms can bind to. Each property that is changed will be set on
			// this proxy, thereby creating an 'own-property' without touching the original value. Only by
			// calling the (non-enumerable, non-modifiable) 'persist' function are the changes copied back
			// to the original which is then returned. Neat trick, huh? ;)
			return Object.create(original, {
				persist: {
					value: function () {
						for (var p in this) {
							if (this.hasOwnProperty(p)) {
								original[p] = this[p];
							}
						}
						return original;
					}
				}
			});
		};
	}


	angular.module('bleedHdApp')

		.service('DateHelper', DateHelperService)

		.service('DataEvents', function (EventChannelFactory) {
			return EventChannelFactory('DataEvents');
		})

		.service('HeaderControl', HeaderControlService)

		.service('ServerLogDump', ServerLogDump)

		.factory('LoginRedirect', LoginRedirect)

		.factory('FormWrapper', FormWrapperFactory)

		/**
		 * The FeatureCheck service is a function that the client uses to determine the availability
		 * of certain features. This is then used to enable / disable the affected UI components.
		 *
		 * This is just to control the UI-side; the server side does its own checks and actually
		 * disables the relevant functionality since it would be way too easy to fool the client.
		 */
		.factory('FeatureCheck', function (BleedHdConfig) {
			return function (feature) {
				// check config feature toggles received from server
				return BleedHdConfig.feature[feature] === true;
			};
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
		 * Wrapper for the $http service that adds BleedHD authorization header to all requests.
		 */
		.factory('BleedHttp', function ($http, AuthHandler) {
			function delegateWithAuth(target, configIndex) {
				return function () {
					var that = this,
						args = Array.prototype.slice.call(arguments),
						config = args[configIndex] || {};

					if (args.length <= configIndex) {
						args.push(config);
					}

					if (config.headers === undefined) {
						config.headers = {};
					}

					return AuthHandler.authorized(function () {
						config.headers['Authorization'] = AuthHandler.getToken();
						return target.apply(that, args);
					});
				}
			}

			var httpWrapper = delegateWithAuth($http, 0);
			angular.extend(httpWrapper, {
				'get': delegateWithAuth($http.get, 1),
				'head': delegateWithAuth($http.head, 1),
				'post': delegateWithAuth($http.post, 2),
				'put': delegateWithAuth($http.put, 2),
				'delete': delegateWithAuth($http.delete, 1),
				'jsonp': delegateWithAuth($http.jsonp, 1),
				'patch': delegateWithAuth($http.patch, 1),
			});

			return httpWrapper;
		})

		/**
		 * The BleedApi service provides a convenient pre-configured Restangular object with
		 * integrated authorization.
		 */
		.factory('BleedApi', function (Restangular, AuthHandler, $window, $log, MessageBuilder, LoginRedirect, BleedHdConfig) {
			return Restangular.withConfig(function(RestangularConfig) {
				RestangularConfig
					.setBaseUrl(BleedHdConfig.api.base)
					.setDefaultHeaders({ 'Authorization': AuthHandler.getToken() })
					.setErrorInterceptor(function (response) {
						var uiError = 'restApiError';

						if (response.status === 403 || response.status === 401) {
							// TODO: split 403 and 401 in separate messages and behavior
							$log.warn('Login required. Redirecting...');
							LoginRedirect();
						} else if (response.status >= 500 && response.status < 600) {
							$log.fatal('REST API error: ' + response.statusText, response.data);
						} else if (response.status === 404 || response.status === 405) {
							$log.error('REST API error: ' + response.statusText, {
								statusCode: response.status,
								method: response.config.method,
								url: response.config.url,
							});
						} else if (response.status === 0) {
							$log.fatal('REST API error: no server response', {
								method: response.config.method,
								url: response.config.url,
							});
							uiError = 'noResponseError';
						} else if (response.status === 498) { // 498: (custom) token expired/invalid
							// this case is already handled with the restangular resource
							// decorator in the core BleedHD module
						} else {
							$log.error('Unknown REST API error', response);
						}

						MessageBuilder.send(uiError, [response.status, response.config === undefined ? null : response.config.method]);

						return true;
					});

					// This piece of code can be used to simulate AJAX timeouts
					//RestangularConfig.setDefaultHttpFields({
					//	timeout: 50,
					//});
			});
		})

		; // finally end the giant statement

})(angular, bleedHd);
