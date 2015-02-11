
(function (angular) {

	var minErr = angular.$$minErr('typeRegistry');

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
		this.behaviorDefs = {};
		this.extensions = extensions;
	}

	angular.extend(TypeRegistryProvider.prototype, {
		$get: function () {
			return angular.extend(new TypeRegistry(this), this.extensions);
		},
		registerType: function (base, typeBuilderFn) {
			this.registerTypeWithName(null, base, typeBuilderFn);
		},
		/**
		 * name, base, [behaviors], typeBuildFn
		 */
		registerTypeWithName: function (name, base, behaviors, typeBuilderFn) {
			// if behaviors is not an array, then that (optional) argument was skipped
			// and it should contain the typeBuilderFn instead
			if (!angular.isArray(behaviors)) {
				typeBuilderFn = behaviors;
				behaviors = [];
			}

			this.typeDefs.push({
				name: name,
				base: base || null,
				behaviors: behaviors,
				builderFn: typeBuilderFn,
				tries: 0,
			});
		},
		registerBehavior: function (name, behavior) {
			this.behaviorDefs[name] = behavior;
		},
		buildTypes: function () {
			var that = this, queue = that.typeDefs, maxTries = 10, typeDef, scopedType, parentType, parent, i, behaviors;

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
					typeDef.tries++;
					queue.push(typeDef);
					continue;
				} else {
					throw "Base type '" + typeDef.base + "' cannot be found";
				}

				behaviors = [];
				for (i = 0; i < typeDef.behaviors.length; i++) {
					if (that.behaviorDefs[typeDef.behaviors[i]] === undefined) {
						throw "Behavior type '" + typeDef.behaviors[i] + "' cannot be found";
					} else {
						behaviors.push(that.behaviorDefs[typeDef.behaviors[i]]);
					}
				}

				scopedType = typeDef.builderFn(that.getParentFunc(parentType));
				var name = typeDef.name || scopedType.ctor.name;

				that.types[name] = that.transformTypeDef(name, scopedType, parentType, behaviors);
			}

			delete(this.typeDefs);
		},
		transformTypeDef: function (name, scopedType, parentType, behaviors) {
			var ctorWrap = new Function("ctor", "return function " + name + "(args) { ctor.apply(this, args); }")(scopedType.ctor);

			// the order of precedence is: parent type, behaviors (in order), explicitly defined members
			// this means that a type's member function always has the highest precedence
			angular.extend.apply(angular, [ctorWrap.prototype, parentType.proto].concat(behaviors).concat(scopedType.members));

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
			if (type === undefined) {
				throw minErr('TypeRegistry', 'Unknown type "{0}"', name);
			}
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
