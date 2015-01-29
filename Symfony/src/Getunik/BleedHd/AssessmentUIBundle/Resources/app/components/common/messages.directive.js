
(function (angular, bleedHd) {

	/**
	 * message format:
	 * {
	 *   type: ('success'|'info'|'warning'|'danger')
	 *   text: HTML message text
	 * }
	 */

	function MessagesController($scope, $element, $attrs, $transclude, $sce, MessageEvents) {
		this.$scope = $scope;
		this.$element = $element;
		this.$sce = $sce;
		this.MessageEvents = MessageEvents;

		this.messages = [];

		this.MessageEvents.on('show-message', this.addMessage.bind(this));
	}

	angular.extend(MessagesController.prototype, {
		dismiss: function (index) {
			this.messages.splice(index, 1);
		},
		addMessage: function (event) {
			event.data.text = this.$sce.trustAsHtml(event.data.text);
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
