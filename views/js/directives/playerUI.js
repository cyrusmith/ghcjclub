angular.module('CjClubUserApp').directive('playerUi', function (jplayerInterface, playlist, radioUrls) {
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
		}
	};
});