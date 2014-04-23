angular.module('CjClubUserApp').directive('playerUi', function (jplayerInterface, playlist) {
	'use strict';
	return {
		restrict: 'EA',
		scope: true,
		controller: 'PlayerCtrl', //TODO перенсти в директиву
		templateUrl: function (el, attr) {
			var tpl = attr.tpl ? attr.tpl : 'player.html';
			return 'views/main/includes/' + tpl;
		},
		link: function (scope, elem) {
			$.each($(elem).find('.sound_control_slider'), function (count, item) {
				//console.log(count);
				$(item).slider({
					animate: true,
					orientation: "vertical",
					range: "min",
					min: 0,
					max: 100,
					value: 100,
					slide: function (event, ui) {
						jplayerInterface.setVolume(ui.value / 100);
						$.each($('.sound_control_slider:not(:eq(' + count + '))'), function (c, i) {
							$(i).slider('value', ui.value);
						});
					}
				});
			});
			scope.$watch(
				function () {
					return jplayerInterface.getVolume();
				},
				function (v) {
					$.each($(elem).find('.sound_control_slider'), function (count, item) {
						$(item).slider('value', v * 100);
					});
				}
			);

			scope.playlist = playlist;

			/*
			 * RADIO
			 */
			var isRadio = false;
			scope.radioBitrateHigh = false;

			scope.setRadioHighBitrate = function(value) {
				if (scope.radioBitrateHigh == value) return;
				scope.radioBitrateHigh = !!value;
				if (scope.isRadio()) {
					scope.playRadio();
				}
			};
			scope.isRadio = function() {
				return isRadio;
			};
			scope.stopRadio = function() {
				isRadio = false;
				var trackIdWas = jplayerInterface.getTrackId();
				if (trackIdWas) {
					jplayerInterface.playId(trackIdWas);
				}
			};
			scope.playRadio = function() {
				isRadio = true;
				var streamUrl = scope.radioBitrateHigh ? "http://www.cjradio.ru:8000/high-stream" : "http://www.cjradio.ru:8000/low-stream";
				jplayerInterface.setMedia(streamUrl);
				jplayerInterface.play();
			};
			scope.toggleRadio = function() {
				if (scope.isRadio()) {
					scope.stopRadio();
				} else {
					scope.playRadio();
				}
			};
		}
	};
});