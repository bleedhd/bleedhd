
(function (angular) {

	function UserDataService(BleedApi) {
		this.BleedApi = BleedApi;
		this.users = BleedApi.all('users');
	}

	angular.extend(UserDataService.prototype, {
		getUser: function (uid) {
			return this.getUsers().then(function (users) {
				return users[uid];
			});
		},
		getUsers: function (patientId) {
			return this.users.getList()
				.then(function (users) {
					var map = {};
					angular.forEach(users, function (user) {
						map[user.id] = user;
					});
					return map;
				});
		},
	});

	angular.module('patient')

		.service('RawUserData', UserDataService)

		.service('UserData', function (CachingWrapper, RawUserData) {
			return CachingWrapper(RawUserData, [
					{
						func: 'getUsers',
						key: function () { return 'users'; },
					},
				]
			);
		})

	;

})(angular);
