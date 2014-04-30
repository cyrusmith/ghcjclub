angular.module('CjClubUserApp').factory('singlePlayer', function ($rootScope, $interval, localStorageService, jplayerInterface) {
	'use strict';
	var guid = function () {
			function s4() {
				return Math.floor((1 + Math.random()) * 0x10000)
					.toString(16)
					.substring(1);
			}

			return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
				s4() + '-' + s4() + s4() + s4();
		},
		run, stop, interval,
		PROP_NAME = 'cj_playing',
		sessionId = guid();

	$rootScope.$watch(
		function () {
			return jplayerInterface.isPlaying();
		},
		function (isPlaying) {
			if (isPlaying) {
				localStorageService.set(PROP_NAME, sessionId);
			}
		}
	);

	run = function () {
		interval = $interval(
			function () {
				var session = localStorageService.get(PROP_NAME);
				if (jplayerInterface.isPlaying() && session !== sessionId) {
					if (jplayerInterface.isRadio()) {
						jplayerInterface.stopRadio();
					}
					jplayerInterface.pause();
				}
			},
			1000
		);
	};

	stop = function () {
		$interval.cancel(interval);
	};

	return {
		run: run,
		stop: stop
	};
});
