App.factory('SignedUser', function() {
	return {
		user: null,
		isSigned: function() {
			return false;
		}
	}
});