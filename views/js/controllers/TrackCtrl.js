angular.module('CjClubUserApp').controller('TrackCtrl', function($scope, Track, jplayerInterface) {
	$scope.track = Track;
	$scope.isBannerCodeShown = false;
	$scope.showBannerCode = function() {
		$scope.isBannerCodeShown = true;
	};
	jplayerInterface.setId($scope.track.id);

	$scope.togglePlay = function() {
		jplayerInterface.togglePlay();
	};
	$scope.isPlaying = function() {
		return jplayerInterface.isPlaying();
	};
	$scope.playInfo = jplayerInterface.info;
	$scope.seekAndPlay = function(e) {
		var x = e.offsetX;
		var width = 970;
		var percents = x / width;
		var seekTo = Math.round($scope.track.timelength * percents);
		jplayerInterface.play(seekTo);
	}
});