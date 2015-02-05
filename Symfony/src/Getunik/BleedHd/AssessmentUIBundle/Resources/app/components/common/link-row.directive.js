
(function (angular, bleedHd) {

	angular.module('common')

		.directive('linkRow', function ($rootScope, $window) {
			return {
				restrict: 'A',
				link: function (scope, element, attributes, controller, transcludeFn) {
					var link = element.find('a');

					attributes.$addClass('link-row');

					element.on('click', function (event) {
						// do custom link row navigation unless the CTRL key is pressed or
						// the clicked element happens to be the actual link (in which case
						// we rely on the fact that links do what they are supposed to do)
						if (!event.ctrlKey && event.target !== link.get(0)) {
							$window.location.href = link.attr('href');
						}
					});
				},
			};
		})

	;

})(angular, bleedHd);
