
(function (angular, bleedHd) {

	angular.module('question')
		.directive('questionMarkup', function ($compile, BleedHdConfig) {

			function preprocess(markup) {
				if (markup === undefined || typeof(markup) !== 'string' || markup === '') {
					return markup;
				}

				var processed = markup;

				// replace @res(...) asset references
				processed = processed.replace(/@res\(([^)]+)\)/g, function(all, g1) { return [BleedHdConfig.assessmentResourcesPath, g1].join('/'); });

				// replace @question(...) link references
				processed = processed.replace(/@question\(([^,]+)\s*,\s*([^)]+)\)/g, function(all, slug, label) { return '<question-link label="' + label + '" slug="' + slug + '"></question-link>'; });

				return processed;
			}

			return {
				restrict: 'A',
				link: function (scope, element, attrs) {

					scope.$watch(
						function (scope) {
							return scope.$eval(attrs.questionMarkup);
						},
						function (newValue, oldValue) {
							element.html(preprocess(newValue));

							$compile(element.contents())(scope);
						}
					);

				},
			};
		});

})(angular, bleedHd);
