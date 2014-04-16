App.controller('SignedUserCtrl', function($scope, SignedUser, $http) {
	$scope.user = SignedUser.user;
	$scope.isSigned = SignedUser.isSigned;


	$scope.model = {
		login: '',
		password: '',
		remember: false
	};
	$scope.sendForm = function() {
		$http.put('api/auth', $scope.model)
			.success(function(userData) {
				$scope.user = SignedUser.user = userData;
			})
			.error(function(msg) {
				alert('Ошибка: ' + msg);
			});
	};
	$scope.logout = function() {
		$http.delete('api/auth')
			.success(function() {
				SignedUser.user = null;
			});
	};
});