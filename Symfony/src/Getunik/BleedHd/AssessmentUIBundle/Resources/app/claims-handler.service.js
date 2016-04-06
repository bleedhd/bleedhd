
(function (angular) {

	var roleHierarchy = {
		'ROLE_SUPER_ADMIN': 100,
		'ROLE_ADMIN': 40,
		'ROLE_SUPERVISOR': 30,
		'ROLE_ASSESSOR': 20,
		'ROLE_READER': 10,
	}

	var claims = {
		canDeleteResource: function (subject) {
			return this.hasRole('ROLE_ADMIN') || (this.hasRole('ROLE_SUPERVISOR') && this.userId === subject.created_by);
		},
	};

	function ClaimsHandler(AuthHandler) {
		var that = this;

		that.userId = -1;
		that.userRoleLevel = 0;

		AuthHandler.authorized(function (authInfo) {
			angular.forEach(authInfo.roles, function (role) {
				if (roleHierarchy[role] !== undefined) {
					that.userRoleLevel = Math.max(that.userRoleLevel, roleHierarchy[role]);
					that.userId = authInfo.uid;
				}
			});
		});
	}

	angular.extend(ClaimsHandler.prototype, {
		permission: function (claim, subject) {
			if (claims[claim] === undefined) {
				return false;
			}

			return claims[claim].call(this, subject);
		},
		hasRole: function (role) {
			return this.userRoleLevel >= roleHierarchy[role];
		},
	});

	angular.module('bleedHdApp')

		.service('ClaimsHandler', ClaimsHandler)

	;

})(angular);
