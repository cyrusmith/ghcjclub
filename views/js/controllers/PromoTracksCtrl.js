App.controller('PromoTracksCtrl', function($scope, TracksResource) {
	$scope.tracks = TracksResource.queryPromo();
});