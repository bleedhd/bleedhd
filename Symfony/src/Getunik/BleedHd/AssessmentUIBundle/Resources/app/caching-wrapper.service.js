
(function (angular) {

	function CachingGetDirective(target, cache, definition) {
		this.target = target;
		this.cache = cache;

		this.key = definition.key;
		this.func = target[definition.func];
		this.lifetime = definition.lifetime || (5 * 60);
	}

	angular.extend(CachingGetDirective.prototype, {
		execute: function () {
			var that = this,
				args = Array.prototype.slice.call(arguments),
				key = that.key.call(that.target, args),
				cacheEntry = that.cache.get(key),
				now = new Date(),
				result;

			if (cacheEntry) {
				if (now < cacheEntry.expiration) {
					console.log('cache hit', cacheEntry);
					return cacheEntry.obj;
				}
				console.log('cache expired');
			}

			result = that.func.apply(that.target, args);

			result.catch(function (error) {
				that.cache.remove(key);
			});

			that.cache.put(key, {
				obj: result,
				expiration: new Date(now.getTime() + that.lifetime * 1000),
			});

			return result;
		},
	});


	function CachingSaveDirective(target, cache, definition) {
		this.target = target;
		this.cache = cache;

		this.key = definition.key;
		this.func = target[definition.func];
		this.lifetime = definition.lifetime || (5 * 60);
		// usually the stored value is the first argument of the function
		this.value = definition.value || this.defaultValue;
	}

	angular.extend(CachingSaveDirective.prototype, {
		execute: function () {
			var that = this,
				args = Array.prototype.slice.call(arguments),
				key = that.key.call(that.target, args),
				val = that.value.call(that.target, args),
				cacheEntry = that.cache.get(key),
				now = new Date(),
				result;

			if (!cacheEntry) {
				return that.func.apply(that.target, args);
			}

			console.log('updating cache entry', key, val);
			that.cache.put(key, {
				obj: val,
				expiration: new Date(now.getTime() + that.lifetime * 1000),
			});

			result = that.func.apply(that.target, args);

			result
				.then(function (data) {
					console.log('post-updating cache entry', key, data);
					that.cache.put(key, {
						obj: data,
						expiration: new Date(now.getTime() + that.lifetime * 1000),
					});
				})
				.catch(function (error) {
					that.cache.remove(key);
				});

			return result;
		},
		defaultValue: function (args) {
			return args[0];
		},
	});


	var directiveTypes = {
		get: CachingGetDirective,
		save: CachingSaveDirective,
	};


	function CachingWrapperFactory($cacheFactory) {

		function wrap (inner, wrapperDefinition) {
			var wrapper = Object.create(inner),
				caches = {};

			angular.forEach(wrapperDefinition, function (definition) {
				var cacheName = definition.cache || 'default',
					type = definition.type || 'get',
					directive;

				if (caches[cacheName] === undefined) {
					caches[cacheName] = $cacheFactory(inner.constructor.name + '.' + cacheName);
				}

				directive = new directiveTypes[type](wrapper, caches[cacheName], definition);
				wrapper[definition.func] = directive.execute.bind(directive);
			});

			return wrapper;
		}

		return wrap;

	}


	angular.module('cachingWrapper', [])

		.factory('CachingWrapper', CachingWrapperFactory)

	;

})(angular);
