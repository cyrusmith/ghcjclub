/**
 * Created by Evgeny on 07.04.2014.
 */
angular.module('CjClubUserApp').directive('addToPlaylist', function (playlist) {
	'use strict';
	var link = function (scope, element) {
		element.on('click', function () {
			if (scope.track) {
				playlist.add(scope.track);
			}
		});
	};

	return {
		restrict: 'A',
		scope: {
			track: '=addToPlaylist'
		},
		link: link
	};
});