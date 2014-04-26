angular.module('CjClubUserApp').directive('playerUi', function (jplayerInterface, playlist, radioUrls) {
	'use strict';
	var
		link = function (scope) {

			scope.playlist = playlist;

			scope.next = function (id) {
				var next = playlist.getNext(id, true);
				if (next) {
					jplayerInterface.playId(next);
				}
			};

			scope.prev = function (id) {
				var prev = playlist.getPrev(id, true);
				if (prev) {
					jplayerInterface.playId(prev);
				}
			};

			/*
			 * RADIO
			 */
			scope.isRadio = false;
			scope.radioBitrateHigh = false;

			scope.setRadioHighBitrate = function (value) {
				if (scope.radioBitrateHigh === value) {
					return;
				}
				scope.radioBitrateHigh = value;
				if (scope.isRadio()) {
					scope.playRadio();
				}
			};
			scope.stopRadio = function () {
				var trackIdWas = jplayerInterface.getTrackId();
				if (trackIdWas) {
					jplayerInterface.playId(trackIdWas);
				}
				scope.isRadio = false;
			};
			scope.playRadio = function () {
				var streamUrl = scope.radioBitrateHigh ? radioUrls.high : radioUrls.low;
				jplayerInterface.setMedia(streamUrl);
				jplayerInterface.play();
				scope.isRadio = true;
			};
			scope.toggleRadio = function () {
				if (scope.isRadio()) {
					scope.stopRadio();
				} else {
					scope.playRadio();
				}
			};
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