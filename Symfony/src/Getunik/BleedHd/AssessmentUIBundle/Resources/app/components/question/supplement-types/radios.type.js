
(function (angular) {

	angular.module('question').config(function (SupplementTypeRegistryProvider) {
		SupplementTypeRegistryProvider.registerTypeWithName('radios', 'base', function (parent) {
			return {
				ctor: function RadiosSupplement (scope, definition) {
					parent(this)(scope, definition);

					// radios supplements cannot have a default value (think about it)

					this.options = $.map(this.definition.options, function (option) {
						return angular.extend({}, option);
					});
				},
				members: {
					link: function (element) {
					},
					getOptions: function () {
						return this.options;
					},
					onChange: function (option) {
						this.scope.$emit('q-supplement-changed', this);
					},
					supplementValue: function (newValue) {
						if (newValue !== undefined) {
							this.supplement[this.definition.slug] = newValue;
						}

						return this.supplement[this.definition.slug];
					},
				},
			};
		});
	});

})(angular);
