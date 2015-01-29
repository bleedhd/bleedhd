
(function (angular) {

	function createDelegateFunc(level) {
		return function () {
			var args = Array.prototype.slice.call(arguments);
			this.log(level, args);
		};
	}

	var mapping = [
		'log',
		'log',
		'info',
		'warn',
		'error',
		'error',
	];



	function LogData() {
		this.storeKey = 'EnhancedLog.LogData.entries';
		this.logEntries = this._getFromLocalStore();
	}

	angular.extend(LogData.prototype, {
		_getFromLocalStore: function () {
			return window.localStorage ? angular.fromJson(window.localStorage.getItem(this.storeKey)) || [] : [];
		},
		add: function (logEntry) {
			this.logEntries.push(logEntry);
			if (window.localStorage) {
				window.localStorage.setItem(this.storeKey, angular.toJson(this.logEntries, false));
			}
		},
		getAll: function () {
			return this.logEntries;
		},
		clear: function () {
			this.logEntries = [];
			if (window.localStorage) {
				window.localStorage.removeItem(this.storeKey);
			}
		},
	});



	function EnhancedLog($log, LogData, config) {
		this.$log = $log;
		this.LogData = LogData;
		this.config = config;
	}

	angular.extend(EnhancedLog.prototype, {
		log: function (level, args) {
			if (level >= this.config.logLevel) {
				if (level >= this.config.storeLogLevel) {
					this._store(level, args);
				}
				this.$log[mapping[level]].apply(this.$log, args);
			}
		},
		debug: createDelegateFunc(0),
		verbose: createDelegateFunc(1),
		info: createDelegateFunc(2),
		warn: createDelegateFunc(3),
		error: createDelegateFunc(4),
		fatal: createDelegateFunc(5),
		_store: function (level, origArgs) {
			var that = this,
				args = [],
				logEntry;

			angular.forEach(origArgs, function(arg) {
				args.push(that._transformError(arg));
			});

			logEntry = {
				timestamp: new Date(),
				level: level,
				user: that.config.getUid() || -1,
				location: window.location.href,
				message: '',
				data: args,
			};

			if (angular.isString(args[0])) {
				logEntry.message = args[0];
				logEntry.data = args.slice(1);
			}

			that.LogData.add(logEntry);
		},
		_transformError: function (arg) {
			if (arg instanceof Error) {
				if (arg.stack) {
					return (arg.message && arg.stack.indexOf(arg.message) === -1) ? 'Error: ' + arg.message + '\n' + arg.stack : arg.stack;
				} else if (arg.sourceURL) {
					return arg.message + '\n' + arg.sourceURL + ':' + arg.line;
				}
			}
			return arg;
		},
	});


	function EnhancedLogConfigProvider() {
		var config = {
			logLevel: 3,
			storeLogLevel: 3,
			getUid: function () { },
		};

		this.DEBUG = 0;
		this.VERBOSE = 1;
		this.INFO = 2;
		this.WARN = 3;
		this.ERROR = 4;
		this.FATAL = 5;

		this.setLogLevel = function (logLevel) {
			config.logLevel = logLevel;
			return this;
		};

		this.setStoreLogLevel = function (logLevel) {
			config.storeLogLevel = logLevel;
			return this;
		};

		this.setUidFunc = function (uidFunc) {
			config.getUid = uidFunc;
			return this;
		};

		var that = this;
		this.$get = function () {
			return config;
		};
	}


	angular.module('enhancedLog', [])

		.service('LogData', LogData)

		.provider('EnhancedLogConfig', EnhancedLogConfigProvider)

		.config(function ($provide) {
			$provide.decorator('$log', function($delegate, LogData, EnhancedLogConfig) {
				return new EnhancedLog($delegate, LogData, EnhancedLogConfig);
			});
		})

	;

})(angular);
