
(function (angular) {

	function CachingDirective(target, cache, definition) {
		this.target = target;
		this.cache = cache;

		this.key = definition.key;
		this.func = target[definition.func];
		this.lifetime = definition.lifetime || (5 * 60);
	}

	angular.extend(CachingDirective.prototype, {
		execute: function () {
			var that = this,
				args = Array.prototype.slice.call(arguments),
				key = that.key.apply(that.target, args),
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


	function CachingWrapperFactory($cacheFactory) {

		function wrap (inner, wrapperDefinition) {
			var wrapper = Object.create(inner),
				caches = {};

			angular.forEach(wrapperDefinition, function (definition) {
				var cacheName = definition.cache || 'default',
					directive;

				if (caches[cacheName] === undefined) {
					caches[cacheName] = $cacheFactory(inner.constructor.name + '.' + cacheName);
				}

				directive = new CachingDirective(wrapper, caches[cacheName], definition);
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
