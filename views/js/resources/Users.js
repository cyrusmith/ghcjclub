App.factory('Tracks', function($resource) {
	return $resource('tracks/:id', {}, {
	});
});