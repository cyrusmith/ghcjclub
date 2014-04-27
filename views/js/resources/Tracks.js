angular.module('CjClubUserApp').factory('TracksResource', function ($resource) {
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
		},
		getWave: {
			method: 'GET',
			url: '_files/waves/:id.json',
			responseType: 'json',
			cache: true,
			transformResponse: function (data) {
				return {points: angular.fromJson(data)};
			}
		},
		getRadio: {
			method: 'GET',
			url: 'radio/0'
		}
	});
});