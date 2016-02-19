
(function (angular, bleedHd) {

	function QuickLinkController($scope, $element, $attrs, $transclude, $location, $q) {

		var ctl = this;

		ctl.$scope = $scope;
		ctl.$location = $location;
		ctl.$q = $q;

		$scope.$watch('assessmentContext', function (newValue, oldValue) {
			if (newValue !== oldValue) {
				ctl._loadQuickLinks(newValue);
			}
		});

		ctl.callback = $scope.callback();
		ctl._loadQuickLinks($scope.assessmentContext);

		this.fullPath = this.$location.path();
		var query = $.param(this.$location.search());

		if (query !== '') {
			this.fullPath += '?' + query;
		}
	}

	angular.extend(QuickLinkController.prototype, {
		_loadQuickLinks: function (context) {
			this.context = context;
			this.quickLinks = context.getQuickLinks();
		},
		isVisible: function () {
			return this.$scope.includeEdit || this.quickLinks.length > 0;
		},
		isActive: function (link) {
			return this.fullPath === this.getLinkPath(link);
		},
		getLinkPath: function (link) {
			var path = ['/assessment', this.context.patient.id, this.context.assessment.id, link.screen].join('/');
			if (link.question) {
				path += '?' + $.param({ q: link.question });
			}
			return path;
		},
		goToLinkPath: function (link) {
			this.$location.path(['/assessment', this.context.patient.id, this.context.assessment.id, link.screen].join('/')).search('q', link.question);
		},
		navigate: function (link) {
			var ctl = this;

			ctl.$q.when(ctl.callback(link))
				.then(function (cbResult) {
					ctl.goToLinkPath(link);
				});
		},
	});

	angular.module('assessment')
		.directive('quickLinks', function() {
			return {
				restrict: 'E',
				scope: {
					assessmentContext: '=',
					includeEdit: '=',
					callback: '&',
				},
				templateUrl: bleedHd.getView('assessment', 'quick-links'),
				controllerAs: 'quickLinkCtl',
				controller: QuickLinkController,
			};
		});

})(angular, bleedHd);
