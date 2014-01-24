App.controller('TracksDiscussedCtrl', function($scope, Tracks) {
	$scope.tracks = Tracks.query();
});