
(function (angular, bleedHd) {

	angular.module('common')

		/**
		 * With Bootstrap modals, there is a potential issue when navigating to a different
		 * route in response to a modal dialog button. While Bootstrap _starts_ to hide the
		 * modal with an animation, the code for an ng-click event is already being executed
		 * and depending on the timing, a route change can happen before the modal is fully
		 * closed. To work around this, the ng-click directive should be avoided for
		 * Bootstrap modals and this bs-modal-action directive should be used instead. It
		 * works just like ng-click, but it waits for the modal to be completely hidden
		 * before executing the action code.
		 */
		.directive('bsModalAction', function ($rootScope) {
			return {
				restrict: 'A',
				link: function (scope, element, attributes, controller, transcludeFn) {
					var modal = element.closest('.modal');

					element.on('click', function () {
						// The hidden.bs.modal event is triggered when the modal has been
						// completely hidden.
						// See http://getbootstrap.com/javascript/#modals-events
						modal.on('hidden.bs.modal', function () {
							scope.$apply(attributes.bsModalAction);
						});
					});
				},
			};
		})

	;

})(angular, bleedHd);
