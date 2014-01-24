App.controller('PromoProjectsCtrl', function($scope, Projects) {
	$scope.projects = Projects.query();
});