
(function (angular, bleedHd) {

	function PagesController($scope, $routeParams, BleedHdConfig, HeaderControl) {
		this.$scope = $scope;

		if ($routeParams.name === 'about') {
			HeaderControl.enableAltLink(BleedHdConfig.login, 'Back to Login');
		}
	}

	bleedHd.registerController('patient', PagesController,
		{
		},
		{
			asName: 'ctlPage',
			templateUrl: function ($routeParams) {
				return bleedHd.getView('pages', 'page-' + $routeParams.name);
			},
		}
	);

})(angular, bleedHd);
