App.factory('Albums', function($resource) {
	return $resource('projects/:projectId/albums/:id', {}, {
	});
});