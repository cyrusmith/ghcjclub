angular.module('CjClubUserApp').controller('PlayerCtrl', function ($scope, $interval, jplayerInterface) {
	'use strict';

	$scope.playInfo = jplayerInterface.info;

	$scope.toggleMute = function () {
		jplayerInterface.toggleMute();
	};
	$scope.togglePlay = function () {
		jplayerInterface.togglePlay();
	};
	$scope.isPlaying = function () {
		return jplayerInterface.isPlaying();
	};
	$scope.seekAndPlay = function (e, width) {
		if (jplayerInterface.isRadio()) {
			return;
		}
		var x = e.offsetX;
		var percents = x / width;
		jplayerInterface.play(Math.round($scope.track.timelength * percents));
	};
});
