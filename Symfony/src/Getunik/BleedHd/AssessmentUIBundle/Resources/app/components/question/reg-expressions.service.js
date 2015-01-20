
(function (angular, bleedHd) {

	var regexExp = /^\/(.*)\/(.*)$/,
		namedExpressions = {
			integer: /^\d*$/,
			decimal: /^\d*(\.\d*)?$/,
		};

	function RegExpressionsService() {
		this.named = namedExpressions;
	}

	angular.extend(RegExpressionsService.prototype, {
		parse: function (expNameString) {
			if (!angular.isString(expNameString) || expNameString === '') {
				return null;
			}

			var exp = this.fromString(expNameString);

			if (exp === null) {
				exp = this.getNamed(expNameString);
			}

			return exp;
		},
		fromString: function (expString) {
			var match = expString.match(regexExp);
			if (match === null)
				return null;

			return new RegExp(match[1], match[2]);
		},
		getNamed: function (name) {
			return this.named[name];
		},
	});


	angular.module('question')

		.service('RegExpressions', RegExpressionsService)

	;

})(angular, bleedHd);
