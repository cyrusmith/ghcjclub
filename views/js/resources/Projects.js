App.factory('Projects', function($resource) {
	return $resource('projects/:id', {}, {
	});
});