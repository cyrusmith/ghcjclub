App.controller('AccountCtrl', ['$scope', 'User', function ($scope, User) {
	$scope.user = User;
	$scope.user.$promise.then(function () {
		$scope.user.regdate = new Date($scope.user.regdate);
		$scope.user.lastdateuse = new Date($scope.user.lastdateuse);
		$scope.user.birthDate =  new Date(1);
		$scope.user.site = "google.com"
		$scope.user.gander = "man";
		$scope.user.description = "Тестовое описание из AccountCtrl. Тестовое описание из AccountCtrl. Тестовое описание из AccountCtrl."
		$scope.user.location = "Россия, Санкт-Петербург"
		$scope.user.contacts = {
			vk: {
				value: "id1",
				isVisible: true
			},
			twitter: {
				value: "putin",
				isVisible: true
			},
			icq: {
				value: 123123,
				isVisible: true
			},
			skype: {
				value: "echo123",
				isVisible: true
			}
		}
	});
}]);