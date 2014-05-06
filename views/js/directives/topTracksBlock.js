App.directive('topTracksBlock', function() {
	return {
		restrict: 'E',
		templateUrl: 'views/main/includes/topTracks.html',
		controller: 'TopTracksCtrl',
		scope: {
			topTrackStyle: '@',
			topTrackTitle: '@'
		}
	}
});