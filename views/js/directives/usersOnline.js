App.directive('usersOnline', function() {
	return {
		restrict: 'E',
		templateUrl: 'views/main/includes/usersOnline.html',
		controller: 'UsersOnlineCtrl',
		link: function () {
		}
	}
});