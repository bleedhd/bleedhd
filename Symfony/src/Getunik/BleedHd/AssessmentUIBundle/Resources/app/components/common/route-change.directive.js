
(function (angular, bleedHd) {

	function RouteChangeController($scope, $element, $attrs, $transclude, $rootScope) {
		this.$scope = $scope;
		this.$element = $element;
		this.$rootScope = $rootScope;

		this.state = this.STATE_VIEW;
	}

	angular.extend(RouteChangeController.prototype, {
		link: function (scope, element, attributes) {
			var that = this;

			this.$rootScope.$on('$routeChangeStart', function (event, current, previous) {
				that.state = that.STATE_LOADING;
			});

			this.$rootScope.$on('$routeChangeSuccess', function (event, current, previous) {
				that.state = that.STATE_VIEW;
			});

			this.$rootScope.$on('$routeChangeError', function (event, current, previous) {
				that.state = that.STATE_ERROR;
			});
		},
		STATE_VIEW: 0,
		STATE_LOADING: 1,
		STATE_ERROR: 2,
	});


	angular.module('common')

		.directive('routeChange', function () {
			return {
				restrict: 'E',
				scope: true,
				transclude: true,
				templateUrl: bleedHd.getView('common', 'route-change'),
				controllerAs: 'routeChangeCtl',
				controller: RouteChangeController,
				link: function (scope, element, attributes, controller) {
					controller.link(scope, element, attributes);
				},
			};
		})

	;

})(angular, bleedHd);
