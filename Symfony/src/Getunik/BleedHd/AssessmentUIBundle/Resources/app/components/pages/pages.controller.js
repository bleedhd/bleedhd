
(function (angular, bleedHd) {

	function PagesController($scope) {
		this.$scope = $scope;
	}

	bleedHd.registerController('patient', PagesController,
		{
		},
		{
			asName: 'ctlPage',
			templateUrl: function ($route) {
				console.log('template route params', $route);
				return bleedHd.getView('pages', 'page-' + $route.name);
			},
		}
	);

})(angular, bleedHd);
