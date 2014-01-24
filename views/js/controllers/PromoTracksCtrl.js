App.controller('PromoTracksCtrl', function($scope, Tracks) {
	$scope.tracks = Tracks.query(function() {});
});