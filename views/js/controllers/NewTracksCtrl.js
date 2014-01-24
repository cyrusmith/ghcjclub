App.controller('NewTracksCtrl', function($scope, Tracks) {
	$scope.tracks = Tracks.query(function() {});
});