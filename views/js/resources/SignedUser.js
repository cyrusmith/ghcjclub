App.factory('SignedUser', function($window) {
	var service = {
		user: null,
		isSigned: function() {
			return angular.isObject(service.user) && (service.user.id > 0);
		}
	};
	if ($window.preloadedDataStorage && $window.preloadedDataStorage.signedUser) {
		service.user = $window.preloadedDataStorage.signedUser;
	}
	return service;
});