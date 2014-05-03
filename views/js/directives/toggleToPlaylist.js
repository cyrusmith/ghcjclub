/**
 * Created by Evgeny on 07.04.2014.
 */
angular.module('CjClubUserApp').directive('toggleToPlaylist', function (playlist) {
		'use strict';
		var link = function (scope, element) {
			element.on('click', function () {
				if (scope.track) {
					if (!playlist.contains(scope.track.id)) {
						playlist.add(scope.track);
					} else {
						playlist.remove(scope.track.id);
					}
				}
			});
			// TODO иконка добавления/удаления
		};

		return {
			restrict: 'A',
			scope: {
				track: '=toggleToPlaylist'
			},
			link: link
		};
	}
)
;