App.factory('jplayerInterface', function($rootScope) {
	var volume = 1;
	var volumeMutted;
	var el;
	var currentTrackId;
	var isPlayingState = false;
	var service = {
		info: {
			playprogress: 0
		},
		init: function(e) {
			el = e;
			el.jPlayer({
				swfPath: 'views/libs/jplayer',
				supplied: 'mp3',
				preload: "auto",
				timeupdate: function(e) {
					service.info.playprogress = Math.round(e.jPlayer.status.currentPercentAbsolute);
					$rootScope.$$phase || $rootScope.$apply();
				}
			});
		},
		setMedia: function(url) {
			el.jPlayer("setMedia", {mp3: url});
		},
		setId: function(trackId) {
			currentTrackId = trackId;
			var url = '_tracks/' + trackId + '.mp3';
			service.setMedia(url);
		},
		play: function(time) {
			el.jPlayer("play", time);
			isPlayingState = true;
		},
		playId: function(trackId) {
			service.setId(trackId);
			service.play();
		},
		playIdOrPause: function(trackId) {
			if (currentTrackId == trackId) {
				service.togglePlay();
			} else {
				service.playId(trackId);
			}
		},
		pause: function() {
			el.jPlayer("pause");
			isPlayingState = false;
		},
		togglePlay: function() {
			if (isPlayingState) {
				service.pause();
			} else {
				service.play();
			}
		},
		isPlaying: function() {
			return isPlayingState;
		},
		isIdbeingPlayed: function(trackId) {
			return (currentTrackId == trackId) && service.isPlaying();
		},
		toggleMute: function() {
			if (volume == 0) {
				service.setVolume(volumeMutted);
			} else {
				volumeMutted = volume;
				service.setVolume(0);
			}
		},
		getVolume: function() {
			return volume;
		},
		setVolume: function(v) {
			volume = v;
			el.jPlayer("volume", v);
		},
		getTrackId: function() {
			return currentTrackId;
		}
	};
	return service;
});