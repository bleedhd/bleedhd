
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
		on: function (names, handler) {
			var that = this;
			if (!angular.isArray(names)) {
				names = [names];
			}

			angular.forEach(names, function (name) {
				if (!that.handlers[name]) {
					that.handlers[name] = [];
				}

				that.handlers[name].push(handler);
			});
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
