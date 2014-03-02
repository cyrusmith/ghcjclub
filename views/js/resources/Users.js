App.factory('UsersResource', function($resource) {
	return $resource('users/:id', {}, {
		queryOnline: {
			url: 'users/online',
			method: 'GET',
			isArray: true
		}
	});
});