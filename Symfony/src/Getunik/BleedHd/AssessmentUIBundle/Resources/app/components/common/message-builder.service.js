
(function (angular) {

	var messages = {
		/**
		 * arg[0] - the HTTP error code
		 * arg[1] - the HTTP request method
		 */
		restApiError: function (args) {
			return {
				type: 'danger',
				text: '<strong>Fatal Error (' + args[0] + '):</strong> An error occurred ' +
						(args[1] === 'GET' ? 'retrieving data from' : 'saving data to') +
						' the server - try <a href="javascript:location.reload()">reloading the page</a>' +
						' and make sure your network connection is working. If you feel that this issue' +
						' should be investigated, please note the time when the error occurred and and provide' +
						' this information to the support team.',
			};
		},
		noResponseError: function (args) {
			return {
				type: 'danger',
				text: '<strong>Fatal Error (no response):</strong> The server did not respond.' +
						' The data ' + (args[0] === 'GET' ? 'retrieval' : 'storage') + ' failed.' +
						' Please make sure your network connection is working and' +
						' <a href="javascript:location.reload()">reload the page</a>.',
			};
		},
		unhandledException: function (args) {
			return {
				type: 'danger',
				text: '<strong>Fatal Error (unhandled exception):</strong> An unhandled error occurred in the' +
						' application. Try <a href="javascript:location.reload()">reloading the page</a> and make' +
						' sure your network connection is working. If you feel that this issue' +
						' should be investigated, please note the time when the error occurred and and provide' +
						' this information to the support team.',
			};
		}
	};


	function MessageBuilder(MessageEvents) {
		this.MessageEvents = MessageEvents;
	}

	angular.extend(MessageBuilder.prototype, {
		send: function (messageId, args) {
			this.MessageEvents.trigger('show-message', messages[messageId](args));
		},
	});


	angular.module('common')

		.service('MessageBuilder', MessageBuilder)

	;

})(angular);
