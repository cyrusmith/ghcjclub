angular.module('CjClubUserApp').controller('PlayerCtrl', function($scope, jplayerInterface, TracksResource) {
	'use strict';
	$scope.track = null;
	/*
	 * при запуске трека, загрузить его и показать в плеере
	 */
	$scope.$watch(
		function() {
			return jplayerInterface.getTrackId();
		},
		function(v) {
			if (angular.isNumber(v)) {
				$scope.track = TracksResource.get({id: v});
			}
		}
	);

	$scope.playInfo = jplayerInterface.info;

	$scope.isTrackSet = function() {
		return $scope.track !== null && $scope.track !== undefined;
	};
	$scope.toggleMute = function() {
		jplayerInterface.toggleMute();
	};
	$scope.togglePlay = function() {
		jplayerInterface.togglePlay();
	};
	$scope.togglePlay = function() {
		jplayerInterface.togglePlay();
	};
	$scope.isPlaying = function() {
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
