angular.module('CjClubUserApp').factory('TracksResource', function($resource) {
	'use strict';
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
		},
		queryByIds: {
			method: 'GET',
			url: 'tracks?id=:ids',
			isArray: true
		}
	});
});