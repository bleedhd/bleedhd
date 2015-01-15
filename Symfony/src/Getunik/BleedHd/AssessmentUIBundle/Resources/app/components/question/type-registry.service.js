
(function (angular, bleedHd) {

	function Type(services, definition) {
		this.services = services;
		this.definition = definition;

		this.template = this.definition.prefix + '-types/' + this.definition.type;

		var varTpl = this.template + '-' + this.definition.variant;
		if (this.services.$templateCache.get(bleedHd.getView('question', varTpl))) {
			this.template = varTpl;
		}
	}

	angular.extend(Type.prototype, {
		instantiate: function (scope, element, definition) {
			var that = this,
				instance = new that.definition.ctor(scope, definition);

			that.services.$templateRequest(bleedHd.getView('question', that.template)).then(function (template) {
				element.append(that.services.$compile(template)(scope));
				instance.linkWithElement(element);
			});

			return instance;
		},
	});

	///////

	function defineType(name, baseCtor) {
		var capitalized = name.charAt(0).toUpperCase() + name.slice(1);

		TypeRegistryService.prototype.types.push(name);

		TypeRegistryService.prototype['get' + capitalized + 'Type'] = function (typeName, variant) {
			ctor = this.registry[name][typeName];

			return ctor === undefined ? null : new Type(this.services, {
				prefix: name,
				type: typeName,
				ctor: ctor,
				variant: variant,
			});
		};

		TypeRegistryService.prototype['register' + capitalized + 'Type'] = function (typeName, ctor, methods) {
			angular.extend(ctor.prototype, baseCtor.prototype, methods);
			this.registry[name][typeName] = ctor;
		};
	}

	function TypeRegistryService($compile, $templateCache, $templateRequest) {
		var that = this;

		that.services = {
			'$compile': $compile,
			'$templateCache': $templateCache,
			'$templateRequest': $templateRequest,
		};

		that.registry = {};
		angular.forEach(that.types, function (typeName) { that.registry[typeName] = {}; });

		window.test = this;
	}

	angular.extend(TypeRegistryService.prototype, {
		types: [],
	});


	///////

	function BaseQuestion() {
	}

	angular.extend(BaseQuestion.prototype, {
		construct: function (scope, question) {
			this.scope = scope;
			this.question = question;
			this.slug = this.question.slug;

			this.data = angular.copy(scope.data()) || this.emptyData();
			// the fun of translating objects to JSON, to PHP array, to JSON (in the DB), back to PHP array,
			// back to JSON, back to objects... {} => JSON {} => PHP array() => JSON [] => PHP array() => JSON [] => []
			if (angular.isArray(this.data.supplements) && this.data.supplements.length === 0) {
				this.data.supplements = {};
			}
		},
		emptyData: function () {
			return {
				value: null,
				supplements: {},
			};
		},
		reset: function (event, data) {
			this.data = this.emptyData();
		},
		linkWithElement: function (element) {
			this.element = element;
			this.baseLink(element);
			this.link(element);
		},
		baseLink: function (element) {
			var that = this;
			this.scope.$on('q-do-reset', function (event, data) {
				that.reset(event, data);
			});

			this.scope.$on('q-supplement-changed', function (event, data) {
				that.scope.$emit('q-data-changed', that.data);
			});
		},
		link: function (element) {
		},
	});

	defineType('question', BaseQuestion);

	///////

	function BaseSupplement() {
	}

	angular.extend(BaseSupplement.prototype, {
		construct: function (scope, definition) {
			this.scope = scope;
			this.definition = definition;
			this.supplement = scope.data();
		},
		linkWithElement: function (element) {
			this.element = element;
			this.baseLink(element);
			this.link(element);
		},
		deactivate: function () {
			this.element.addClass('disabled');
		},
		setActive: function (active) {
			this.element.toggleClass('disabled', !active);
		},
		baseLink: function (element) {
			var that = this;

			that.scope.$watch('active', function (newValue) {
				that.setActive(newValue);
			});

			that.setActive(that.scope.active);
		},
		link: function (element) {
		},
	});

	defineType('supplement', BaseSupplement);

	///////

	angular.module('question')
		.service('TypeRegistry', TypeRegistryService);

})(angular, bleedHd);
