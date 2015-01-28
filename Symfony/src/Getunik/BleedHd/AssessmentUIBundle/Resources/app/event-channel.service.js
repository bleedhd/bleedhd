
(function (angular) {

	function EventChannel(name) {
		this.name = name;
		this.handlers = {};
	}

	angular.extend(EventChannel.prototype, {
		trigger: function (name, data, props) {
			var event = {
				name: name,
				data: data,
			};

			if (!!props) {
				angular.extend(event, props);
			}

			angular.forEach(this.handlers[name], function (handler) {
				handler(event);
			});
		},
		on: function (name, handler) {
			if (!this.handlers[name]) {
				this.handlers[name] = [];
			}

			this.handlers[name].push(handler);
		},
	});


	function EventChannelFactory() {

		function create(name) {
			return new EventChannel(name);
		}

		return create;

	}


	angular.module('eventChannel', [])

		.factory('EventChannelFactory', EventChannelFactory)

	;

})(angular);
