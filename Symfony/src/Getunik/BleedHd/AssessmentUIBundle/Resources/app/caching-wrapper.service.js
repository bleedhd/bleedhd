
(function (angular) {

	var defaultLifetime = {
		value: 15 * 60, // 15 minutes
		getExpirationDate: function () {
			return new Date(Date.now() + this.value * 1000);
		},
	};

	function CachingGetDirective($log, target, cache, definition) {
		this.$log = $log;
		this.target = target;
		this.cache = cache;

		this.key = definition.key;
		this.func = target[definition.func];
		this.lifetime = definition.lifetime;
	}

	angular.extend(CachingGetDirective.prototype, {
		execute: function () {
			var that = this,
				args = Array.prototype.slice.call(arguments),
				key = that.key.apply(that.target, args),
				cacheEntry = that.cache.get(key),
				now = new Date(),
				result;

			if (cacheEntry) {
				if (now < cacheEntry.expiration) {
					that.$log.verbose('cache hit', key, cacheEntry);
					return cacheEntry.obj;
				}
				that.$log.verbose('cache expired', key);
			}

			result = that.func.apply(that.target, args);

			result.then(
				function (data) {
					that.cache.put(key, {
						obj: result,
						expiration: that.lifetime.getExpirationDate(),
					});
				},
				function (error) {
					that.cache.remove(key);
				}
			);

			return result;
		},
	});


	function CachingSaveDirective($log, target, cache, definition) {
		this.$log = $log;
		this.target = target;
		this.cache = cache;

		this.key = definition.key;
		this.func = target[definition.func];
		this.lifetime = definition.lifetime;
		// usually the stored value is the first argument of the function
		this.value = definition.value || this.defaultValue;
	}

	angular.extend(CachingSaveDirective.prototype, {
		execute: function () {
			var that = this,
				args = Array.prototype.slice.call(arguments),
				key = that.key.apply(that.target, args),
				val = that.value.apply(that.target, args),
				cacheEntry = that.cache.get(key),
				now = new Date(),
				result;

			if (!cacheEntry) {
				return that.func.apply(that.target, args);
			}

			that.$log.verbose('updating cache entry', key, val);
			that.cache.put(key, {
				obj: val,
				expiration: that.lifetime.getExpirationDate(),
			});

			result = that.func.apply(that.target, args);

			result
				.then(function (data) {
					that.cache.put(key, {
						obj: data,
						expiration: that.lifetime.getExpirationDate(),
					});
				})
				.catch(function (error) {
					that.cache.remove(key);
				});

			return result;
		},
		defaultValue: function (arg) {
			return arg;
		},
	});


	var directiveTypes = {
		get: CachingGetDirective,
		save: CachingSaveDirective,
	};


	function CachingWrapperProvider($cacheFactoryProvider) {

		function wrap ($cacheFactory, $log, inner, wrapperDefinition, initFunc) {
			var wrapper = Object.create(inner),
				caches = {};

			// every caching wrapper has its own configurable default lifetime
			// based on the global defaultLifetime helper object
			wrapper.lifetime = Object.create(defaultLifetime);
			wrapper.setDefaultLifetime = function (lifetime) {
				wrapper.lifetime.value = lifetime;
				return this;
			};

			angular.forEach(wrapperDefinition, function (definition) {
				var cacheName = definition.cache || 'default',
					type = definition.type || 'get',
					directive,
					// each directive has its lifetime based on the wrapper's which in turn
					// is based on the default
					lifetime = Object.create(wrapper.lifetime);

				if (caches[cacheName] === undefined) {
					caches[cacheName] = $cacheFactory(inner.constructor.name + '.' + cacheName);
				}

				// the wrapper definition contains the lifetime for the directive as a simple value (if any)
				// and has to be set on the convenient lifetime object.
				if (definition.lifetime) {
					lifetime.value = definition.lifetime;
				}

				definition.lifetime = lifetime;

				directive = new directiveTypes[type]($log, wrapper, caches[cacheName], definition);
				wrapper[definition.func] = directive.execute.bind(directive);
			});

			wrapper.caches = caches;

			if (!!initFunc) {
				wrapper.init = initFunc;
				wrapper.init();
			}

			return wrapper;
		}

		//return wrap;
		this.$get = function ($cacheFactory, $log) { return wrap.bind(this, $cacheFactory, $log); };

		this.setDefaultLifetime = function (lifetime) {
			defaultLifetime.value = lifetime;
		};

	}


	angular.module('cachingWrapper', [])

		.provider('CachingWrapper', CachingWrapperProvider)

	;

})(angular);
