angular.module('CjClubUserApp').factory('StylesResource', function($resource) {
	'use strict';
	return $resource('styles/:id');
});
