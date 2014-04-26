angular.module('CjClubUserApp').directive('playerUi', function (jplayerInterface, playlist) {
	'use strict';
	var
		link = function (scope) {

			scope.playlist = playlist;

			scope.next = function (id) {
				if (jplayerInterface.isRadio()) {
					return;
				}
				var next = playlist.getNext(id, true);
				if (next) {
					jplayerInterface.playId(next);
				}
			};

			scope.prev = function (id) {
				if (jplayerInterface.isRadio()) {
					return;
				}
				var prev = playlist.getPrev(id, true);
				if (prev) {
					jplayerInterface.playId(prev);
				}
			};

			scope.isRadio = jplayerInterface.isRadio;
			scope.radioBitrateHigh = jplayerInterface.isBitrateHigh;
			scope.setRadioHighBitrate = jplayerInterface.setRadioHighBitrate;
			scope.playRadio = jplayerInterface.playRadio;
			scope.stopRadio = jplayerInterface.stopRadio;
		};

	return {
		restrict: 'EA',
		scope: true,
		controller: 'PlayerCtrl', //TODO перенсти в директиву
		templateUrl: function (el, attr) {
			var tpl = attr.tpl ? attr.tpl : 'player.html';
			return 'views/main/includes/' + tpl;
		},
		link: link
	};
});