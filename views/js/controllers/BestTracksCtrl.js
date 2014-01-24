App.controller('BestTracksCtrl', function($scope, Tracks) {
	$scope.tracks = Tracks.query(function() {});
});