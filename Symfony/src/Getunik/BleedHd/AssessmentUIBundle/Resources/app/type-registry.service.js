
(function (angular) {

	function TypeRegistryFactory() {
		this.instances = {};

		console.log('TypeRegistryFactory');

		var that = this;
		this.$get = function () { return that; };
	}

	angular.extend(TypeRegistryFactory.prototype, {
		create: function (name) {
			this.instances[name] = new TypeRegistryProvider();
			return this.instances[name];
			/*return function () {

			};
			this.instances[name] = new TypeRegistryProvider()*/
		},
		buildTypes: function () {
			angular.forEach(this.instances, function (inst) {
				inst.buildTypes();
			});
		},
	});


	function TypeRegistryProvider() {
		console.log('TypeRegistryProvider');

		this.typeDefs = [];
	}

	angular.extend(TypeRegistryProvider.prototype, {
		$get: function () {
			return new TypeRegistry(this);
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
		console.log('TypeRegistry');
	}

	angular.extend(TypeRegistry.prototype, {
		instantiate: function (name, args) {
			console.log('instantiating', name, args);

			var type = this.provider.types[name];
			return new type.ctor(args);
		},
		exists: function (name) {

		},
	});

	angular.module('typeRegistry', [])

		.provider('TypeRegistryFactory', TypeRegistryFactory)

		.provider('MyRegistry', function (TypeRegistryFactoryProvider) {
			return TypeRegistryFactoryProvider.create('MyRegistry');
		})

		.config(function (MyRegistryProvider) {
			MyRegistryProvider.registerType("MyType", function (parent) {
				return {
					ctor: function DerivedType (a) { console.log("parent ctor", parent(this)); parent(this)(a); console.log("DerivedType", a); this.b = a; },
					members: {
						test: function () { console.log('test derived'); parent(this, "test")(); return "result"; }
					}
				};
			});
		})

		.config(function (MyRegistryProvider) {
			MyRegistryProvider.registerType(null, function (parent) {
				return {
					ctor: function MyType (a) { console.log("MyType", a); this.a = a; },
					members: {
						test: function () { console.log('test'); return "result"; },
						myTest: function () { console.log('mytest'); },
					}
				};
			});
		})

		.run(function (TypeRegistryFactory, MyRegistry) {
			TypeRegistryFactory.buildTypes();

			console.log("running....");
			var inst = MyRegistry.instantiate('MyType', [42]);
			console.log(inst);
			console.log(inst.test());
		})

		.run(function (TypeRegistryFactory, MyRegistry) {
			console.log("running....");
			var inst = MyRegistry.instantiate('DerivedType', ["gurray"]);
			console.log(inst);
			console.log(inst.test());
		})

	;

})(angular);
