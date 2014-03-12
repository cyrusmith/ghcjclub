angular.module('CjClubUserApp').controller('TrackCtrl', function($scope, Track, jplayerInterface) {
	$scope.track = Track;
	$scope.isBannerCodeShown = false;
	$scope.showBannerCode = function() {
		$scope.isBannerCodeShown = true;
	};
	$scope.player = jplayerInterface;
	$scope.playInfo = jplayerInterface.info;
	$scope.getPlayProgress = function() {
		if ($scope.player.getId() != $scope.track.id) return 0;
		return $scope.playInfo.playprogress;
	};
	$scope.seekAndPlay = function(e) {
		if ($scope.player.getId() != $scope.track.id) return;
		var x = e.offsetX;
		var width = 970;
		var percents = x / width;
		var seekTo = Math.round($scope.track.timelength * percents);
		jplayerInterface.play(seekTo);
	}
});