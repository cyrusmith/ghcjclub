angular.module('CjClubUserApp').directive('jplayer', function ($window, jplayerInterface, playlist) {
	'use strict';
	var defaultOptions = {
		autoNext: false
	};

	return {
		restrict: 'EA',
		replace: true,
		template: '<div id="jplayercontainer">Player is here</div>',
		link: function (scope, element, attrs) {
			var options = angular.extend({}, defaultOptions, attrs);
			jplayerInterface.init(element, options);

			if ($window.preloadedData && $window.preloadedData.trackIdInPlayer) {
				jplayerInterface.setId($window.preloadedData.trackIdInPlayer);
			} else {
				var id = playlist.isEmpty() ? 51086 : playlist.firstId();
				jplayerInterface.setId(id);
			}

			scope.$on('$destroy', function () {
				jplayerInterface.destroy(element, options);
			});
		}
	};
});