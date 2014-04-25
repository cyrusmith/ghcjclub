angular.module('CjClubUserApp').controller('ProjectCtrl', ['$scope', 'Project', 'Albums', 'Tracks', 'Blogs', 'SignedUser', function($scope, Project, Albums, Tracks, Blogs, SignedUser) {
	$scope.project = Project;
	$scope.project.$promise.then(function() {
		$scope.project.styles = [
			{
				id: 2,
				style: 'Trance'
			},
			{
				id: 14,
				style: 'DnB'
			}
		];
	}); // todo mockup
	$scope.blog = Blogs;
	$scope.tracks = Tracks;
	$scope.albums = Albums;

	console.log(SignedUser);
	/*
	todo проверять на наличие прав администрирования проекта
	if (SignedUser.isSigned() && (SignedUser.id == Project.)) {
	}
	*/
	$scope.isAccessGranted = false; // доступ к изменению
}]);