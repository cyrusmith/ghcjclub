App.directive('usersOnline', function(UsersResource) {
	return {
		restrict: 'E',
		templateUrl: 'views/main/includes/usersOnline.html',
		//controller: 'UsersOnlineCtrl',
		scope: true,
		link: function (scope) {
			scope.users = UsersResource.queryOnline();
		}
	}
});