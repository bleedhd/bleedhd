
(function (angular, bleedHd) {

	/**
	 * message format:
	 * {
	 *   type: ('success'|'info'|'warning'|'danger')
	 *   text: HTML message text
	 *   persistent: (true|false) - defaults to false
	 * }
	 */

	function MessagesController($scope, $element, $attrs, $transclude, $sce, MessageEvents) {
		this.$scope = $scope;
		this.$element = $element;
		this.$sce = $sce;
		this.MessageEvents = MessageEvents;

		this.messages = [];

		this.MessageEvents.on('show-message', this.addMessage.bind(this));

		var that = this;
		this.$scope.$on('$routeChangeStart', function (event, current, previous) {
			that.autoDismiss();
		});
	}

	angular.extend(MessagesController.prototype, {
		dismiss: function (index) {
			this.messages.splice(index, 1);
		},
		autoDismiss: function () {
			var now = Date.now(),
				cleaned = [];

			angular.forEach(this.messages, function (msg) {
				if (msg.persistent === true || (now - msg.timestamp) < 2000) {
					cleaned.push(msg);
				}
			});

			this.messages = cleaned;
		},
		addMessage: function (event) {
			event.data.text = this.$sce.trustAsHtml(event.data.text);
			event.data.timestamp = Date.now();
			this.messages.push(event.data);
		},
	});


	angular.module('common')

		.directive('messages', function ($rootScope) {
			return {
				restrict: 'E',
				scope: true,
				templateUrl: bleedHd.getView('common', 'messages'),
				controllerAs: 'messagesCtl',
				controller: MessagesController,
			};
		})

		.service('MessageEvents', function (EventChannelFactory) {
			return EventChannelFactory('MessageEvents');
		})

	;

})(angular, bleedHd);
