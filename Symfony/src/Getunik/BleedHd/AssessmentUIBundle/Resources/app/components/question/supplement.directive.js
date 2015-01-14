
(function (angular, bleedHd) {

	function BaseSupplement() {
	}

	angular.extend(BaseSupplement.prototype, {
		construct: function (scope, definition) {
			this.scope = scope;
			this.definition = definition;
			this.supplement = scope.data();

			scope.register({ supplement: this });
		},
		link: function (element) {},
	});


	function CheckboxSupplement(scope, definition) {
		this.construct(scope, definition);
	}

	angular.extend(CheckboxSupplement.prototype, BaseSupplement.prototype, {
		link: function (element) {
			var that = this;

			this.scope.$watch('value', function (newValue, oldValue) {
				if (newValue !== oldValue && newValue !== null) {
					that.supplement[that.definition.slug] = newValue;
					that.scope.$emit('supplement-changed', that.value);
				}
			});
		},
	});


	var supplementTypes = {
		checkbox: CheckboxSupplement,
	};


	//////

	angular.module('question')
		.directive('supplement', function ($templateRequest, $compile) {
			return {
				restrict: 'E',
				scope: {
					register: '&',
					definition: '&',
					data: '&',
				},
				compile: function (element, attrs, transclude) {
					// return simple linking function that dynamically loads the question template
					return function (scope, element, attrs) {

						var definition = scope.definition();
						scope.supplementCtl = new supplementTypes[definition.type](scope, definition);

						$templateRequest(bleedHd.getView('question', 'supplement-type-' + definition.type)).then(function (template) {
							element.append($compile(template)(scope));
							scope.supplementCtl.link(element);
						});
					};
				},
			};
		});

})(angular, bleedHd);
