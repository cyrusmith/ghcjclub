App.controller('SignedUserCtrl', function($scope, SignedUser) {
	$scope.user = SignedUser.user;
	$scope.isSigned = SignedUser.isSigned();
});