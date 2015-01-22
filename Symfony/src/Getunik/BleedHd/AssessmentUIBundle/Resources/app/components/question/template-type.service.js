
(function (angular, bleedHd) {

	function TemplateTypeService($compile, $templateCache, $templateRequest) {
		this.$compile = $compile;
		this.$templateCache = $templateCache;
		this.$templateRequest = $templateRequest;
	}

	angular.extend(TemplateTypeService.prototype, {
		instantiate: function (typeRegistry, scope, element, definition) {
			var that = this,
				base = typeRegistry.prefix + '-types/',
				instance = typeRegistry.instantiate(definition.type, [scope, definition]),
				templateName = that.findTemplate(base, instance);

			that.$templateRequest(bleedHd.getView('question', templateName)).then(function (template) {
				element.append(that.$compile(template)(scope));
				instance.link(element);
			});

			return instance;
		},
		findTemplate: function (base, instance) {
			var that = this,
				template = null;

			$.each(instance.getTemplateHierarchy(), function (index, name) {
				if (that.$templateCache.get(bleedHd.getView('question', base + name))) {
					template = base + name;
					return false;
				}
			});

			return template;
		},
	});


	angular.module('question')

		.service('TemplateTypeService', TemplateTypeService)

		.provider('QuestionTypeRegistry', function (TypeRegistryFactoryProvider) {
			return TypeRegistryFactoryProvider.create('QuestionTypeRegistry', { prefix: 'question' });
		})

		.provider('SupplementTypeRegistry', function (TypeRegistryFactoryProvider) {
			return TypeRegistryFactoryProvider.create('SupplementTypeRegistry', { prefix: 'supplement' });
		})

	;

})(angular, bleedHd);
