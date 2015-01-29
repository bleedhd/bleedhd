
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
		'fatal',
	];

	function ServerLog($log, config) {
		this.$log = $log;
		this.config = config;
		this.logEntries = this._retrieve();
		this.storeKey = 'ServerLog.entries';

		window.setInterval(this._dump.bind(this), this.config.dumpInterval * 60 * 1000);
		this._dump();
	}

	angular.extend(ServerLog.prototype, {
		DEBUG: 0,
		VERBOSE: 1,
		INFO: 2,
		WARN: 3,
		ERROR: 4,
		FATAL: 5,
		log: function (level, args) {
			if (level >= this.config.logLevel) {
				this._store(level, args);
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
				user: -1,
				location: window.location.href,
				message: '',
				data: args,
			};

			if (angular.isString(args[0])) {
				logEntry.message = args[0];
				logEntry.data = args.slice(1);
			}

			that.logEntries.push(logEntry);

			if (window.localStore) {
				window.localStore.setItem(that.storeKey, angular.toJson(that.logEntries, false));
			}
		},
		_retrieve: function () {
			return window.localStore ? window.localStore.getItem(this.storeKey) || [] : [];
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
		_dump: function () {
			console.log('dumping log', this.logEntries);

			if (this.logEntries.length > 0) {
				this.logEntries = [];
				if (window.localStore) {
					window.localStore.removeItem(this.storeKey);
				}
			}
		},
	});


	function ServerLogConfigProvider() {
		this.config = {
			logLevel: 0,
			dumpInterval: 1, // 15 minutes
		};

		var that = this;
		this.$get = function () {
			return that.config;
		};
	}


	angular.module('serverLog', [])

		.provider('ServerLogConfig', ServerLogConfigProvider)

		.config(function ($provide) {
			$provide.decorator('$log', function($delegate, ServerLogConfig) {
				return new ServerLog($delegate, ServerLogConfig);
			});
		})

	;

})(angular);
