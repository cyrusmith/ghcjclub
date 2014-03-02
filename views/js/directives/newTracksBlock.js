App.directive('newTracksBlock', function(jplayerInterface, TracksResource) {
	return {
		restrict: 'EA',
		scope: true,
		link: function(scope) {
			scope.player = jplayerInterface;
			scope.tracks = TracksResource.queryLatest(function() {});
		}
	}
});