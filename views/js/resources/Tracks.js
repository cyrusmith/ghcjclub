App.factory('TracksResource', function($resource) {
	return $resource('tracks/:id', {}, {
		queryBest: {
			method: 'GET',
			url: 'tracks/best',
			isArray: true
		},
		queryLatest: {
			method: 'GET',
			url: 'tracks/latest',
			isArray: true
		},
		queryPromo: {
			method: 'GET',
			url: 'tracks/promo',
			isArray: true
		}
	});
});