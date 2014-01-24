App.factory('Blogs', function($resource) {
	return $resource('blogs/:id', {}, {
	});
});