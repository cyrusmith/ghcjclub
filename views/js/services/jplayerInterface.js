angular.module('CjClubUserApp').factory('jplayerInterface', function ($rootScope, $interval, playlist, radioUrls, TracksResource, radioUpdateTime) {
	'use strict';
	var
		volume = 1,
		volumeMutted,
		el,
		currentTrackId,
		isRadio,
		radioBitrateHigh = true,
		isPlayingState,
		radioUpdateInterval,
		currentTrack,
		updateCurrent = function (id) {
			if (angular.isNumber(id)) {
				currentTrack = TracksResource.get({id: id});
				$rootScope.$broadcast('jplayerInterface:trackUpdated', currentTrack);
			}
		},
		updateRadioInfo = function () {
			currentTrack = TracksResource.getRadio();
			$rootScope.$broadcast('jplayerInterface:trackUpdated', currentTrack);

			if (angular.isUndefined(radioUpdateInterval)) {
				radioUpdateInterval = $interval(
					function () {
						currentTrack = TracksResource.getRadio();
						$rootScope.$broadcast('jplayerInterface:trackUpdated', currentTrack);
					},
					radioUpdateTime
				);
			}
		},
		stopUpdateRadio = function () {
			$interval.cancel(radioUpdateInterval);
			radioUpdateInterval = undefined;
			updateCurrent(service.getTrackId());
		},
		service = {
			info: {
				position: 0,
				durationEstimate: 100,
				bytesLoaded: 0,
				bytesTotal: 100
			},
			init: function (e, options) {
				var self = this;
				el = e;
				el.jPlayer({
					swfPath: 'views/libs/jplayer',
					supplied: 'mp3',
					preload: "auto",
					timeupdate: function (e) {
						service.info.position = e.jPlayer.status.currentPercentAbsolute;
						if (!$rootScope.$$phase) {
							$rootScope.$apply();
						}
					},
					progress: function (e) {
						service.info.bytesLoaded = e.jPlayer.status.seekPercent;
						if (!$rootScope.$$phase) {
							$rootScope.$apply();
						}
					}
				});

				if (options.autoNext) {
					el.bind($.jPlayer.event.ended, function () {
						var next = playlist.getNext(currentTrackId);
						if (next) {
							self.playId(next);
						}
					});
				}
			},
			destroy: function (el, options) {
				if (options.autoNext) {
					el.unbind($.jPlayer.event.ended);
				}
			},
			setMedia: function (url) {
				el.jPlayer("setMedia", {mp3: url});
			},
			setId: function (trackId) {
				service.info.position = 0;
				service.info.bytesLoaded = 0;

				currentTrackId = trackId;
				var url = '_tracks/' + trackId + '.mp3';
				service.setMedia(url);
				updateCurrent(trackId);
			},
			getId: function () {
				return currentTrackId;
			},
			play: function (time) {
				el.jPlayer("play", time);
				isPlayingState = true;
			},
			playId: function (trackId) {
				service.setId(trackId);
				service.play();
			},
			playIdOrPause: function (trackId) {
				if (isRadio) {
					service.stopRadio();
				}

				if (currentTrackId === trackId) {
					service.togglePlay();
				} else {
					service.playId(trackId);
				}
			},
			pause: function () {
				el.jPlayer("pause");
				isPlayingState = false;
			},
			togglePlay: function () {
				if (isRadio) {
					return;
				}

				if (isPlayingState) {
					service.pause();
				} else {
					service.play();
				}
			},
			isPlaying: function () {
				return isPlayingState;
			},
			isRadio: function () {
				return isRadio;
			},
			isIdbeingPlayed: function (trackId) {
				return (currentTrackId === trackId) && service.isPlaying() && !isRadio;
			},
			toggleMute: function () {
				if (volume === 0) {
					service.setVolume(volumeMutted);
				} else {
					volumeMutted = volume;
					service.setVolume(0);
				}
			},
			getVolume: function () {
				return volume;
			},
			setVolume: function (v) {
				volume = v;
				el.jPlayer("volume", v);
			},
			getTrackId: function () {
				return currentTrackId;
			},
			playRadio: function () {
				var streamUrl = radioBitrateHigh ? radioUrls.high : radioUrls.low;
				service.setMedia(streamUrl);
				service.play();
				isRadio = true;
				updateRadioInfo();
			},
			stopRadio: function () {
				var prevTrackId = service.getTrackId();
				if (prevTrackId) {
					service.playId(prevTrackId);
				}
				isRadio = false;
				stopUpdateRadio();
			},
			setRadioHighBitrate: function (value) {
				if (radioBitrateHigh === value) {
					return;
				}
				radioBitrateHigh = value;
				if (isRadio) {
					service.playRadio();
				}
			},
			isBitrateHigh: function () {
				return radioBitrateHigh;
			},
			getCurrentTrack: function () {
				return currentTrack;
			}
		};

	return service;
});
