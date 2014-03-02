App.controller('TracksDiscussedCtrl', function($scope, TracksResource) {
	$scope.tracks = TracksResource.query();
});