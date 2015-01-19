
(function (angular) {

	function TypeRegistryFactory() {
		this.instances = {};

		var that = this;
		this.$get = function () { return that; };
	}

	angular.extend(TypeRegistryFactory.prototype, {
		create: function (name, extensions) {
			this.instances[name] = new TypeRegistryProvider(extensions);
			return this.instances[name];
		},
		buildTypes: function () {
			angular.forEach(this.instances, function (inst) {
				inst.buildTypes();
			});
		},
	});


	function TypeRegistryProvider(extensions) {
		this.typeDefs = [];
		this.extensions = extensions;
	}

	angular.extend(TypeRegistryProvider.prototype, {
		$get: function () {
			return angular.extend(new TypeRegistry(this), this.extensions);
		},
		registerType: function (base, typeBuilderFn) {
			this.registerTypeWithName(null, base, typeBuilderFn);
		},
		registerTypeWithName: function (name, base, typeBuilderFn) {
			this.typeDefs.push({
				name: name,
				base: base || null,
				builderFn: typeBuilderFn,
			});
		},
		buildTypes: function () {
			var that = this, queue = that.typeDefs, typeDef, scopedType, parentType, parent;

			that.types = {};

			while (queue.length > 0) {
				typeDef = queue.shift();

				if (typeDef.base === null) {
					parentType = {
						ctor: null,
						proto: {},
					};
				} else if (that.types[typeDef.base] !== undefined) {
					parentType = that.types[typeDef.base];
				} else if (queue.length > 0) {
					queue.push(typeDef);
					continue;
				} else {
					throw "Base type '" + typeDef.base + "' cannot be found";
				}

				scopedType = typeDef.builderFn(that.getParentFunc(parentType));
				var name = typeDef.name || scopedType.ctor.name;

				that.types[name] = that.transformTypeDef(name, scopedType, parentType);
			}

			delete(this.typeDefs);
		},
		transformTypeDef: function (name, scopedType, parentType) {
			var ctorWrap = new Function("ctor", "return function " + name + "(args) { ctor.apply(this, args); }")(scopedType.ctor);

			angular.extend(ctorWrap.prototype, parentType.proto, scopedType.members);

			return {
				ctor: ctorWrap,
				ctorInt: scopedType.ctor,
				proto: ctorWrap.prototype,
			};
		},
		getParentFunc: function (parentType) {
			return function (obj, name) {
				if (name === undefined) {
					return parentType.ctorInt.bind(obj);
				} else {
					return parentType.proto[name].bind(obj);
				}
			};
		},
	});

	function TypeRegistry(provider) {
		this.provider = provider;
	}

	angular.extend(TypeRegistry.prototype, {
		instantiate: function (name, args) {
			var type = this.provider.types[name];
			return new type.ctor(args);
		},
		exists: function (name) {
			return this.provider.types[name] !== undefined;
		},
	});

	angular.module('typeRegistry', [])

		.provider('TypeRegistryFactory', TypeRegistryFactory)

		.run(function (TypeRegistryFactory) {
			TypeRegistryFactory.buildTypes();
		})

	;

})(angular);
