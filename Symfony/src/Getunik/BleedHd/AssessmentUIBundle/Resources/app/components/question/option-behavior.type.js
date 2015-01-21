
(function (angular) {

	var optionBehavior = {
		/**
		 * The returned binding object must have the following properties:
		 * - value: primitive - the option value
		 * - supplements: object - a supplements container object for the option; this will be used by the supplements to bind to
		 */
		processOptions: function (options, bindingCallback) {
			var that = this;
			return $.map(options, function (option) {
				return angular.extend({}, option, {	binding: bindingCallback(option) });
			});
		},
		getOptions: function () {
			return this.options;
		},
	};

	angular.module('question').config(function (QuestionTypeRegistryProvider, SupplementTypeRegistryProvider) {
		QuestionTypeRegistryProvider.registerBehavior('option', optionBehavior);
		SupplementTypeRegistryProvider.registerBehavior('option', optionBehavior);
	});

})(angular);
