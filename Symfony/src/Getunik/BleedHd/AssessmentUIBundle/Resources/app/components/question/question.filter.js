
(function (angular) {

	angular.module('question')

		.filter('questionProcessor', function (BleedHdConfig, $filter) {
			return function (text) {
				if (text === undefined || typeof(text) !== 'string' || text === '') {
					return text;
				}

				var processed = text;

				// replace @res(...) asset references
				processed = processed.replace(/@res\(([^)]+)\)/g, function(all, g1) { return [BleedHdConfig.assessmentResourcesPath, g1].join('/'); });

				return processed;
			};
		})

		;

})(angular);
