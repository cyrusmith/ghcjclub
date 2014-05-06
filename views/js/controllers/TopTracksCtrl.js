App.controller('TopTracksCtrl', function ($scope, TracksResource, Projects) {

	$scope.$watch("topTrackStyle", function(){
		$scope.topTracks = TracksResource.query({
			style: $scope.topTrackStyle,
			sort: "pit"
		});
	})

	$scope.getProject = function (id) {
		Projects.get({id: $scope.topTracks[id].projectId}).$promise.then(function (result) {
			$scope.topTracks[id].project = result.name;
		});
	}
});