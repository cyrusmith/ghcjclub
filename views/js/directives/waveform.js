angular.module('CjClubUserApp').directive('waveform', function ($rootScope, jplayerInterface, TracksResource) {
	'use strict';
	var
		colors = {
			defaultColor: '#575757',
			loadedColor: '#ffffff',
			playedColor: '#de8200'
		},
		link = function (scope, element) {
			var
				parant = element.parent(),
				waveform = new Waveform({
					container: element[0],
					width: parant.width(),
					height: parant.height()
				}),
				streamOptions = waveform.optionsForSyncedStream(colors);

			streamOptions.whileloading.call(jplayerInterface.info);

			scope.$watch(
				function () {
					return jplayerInterface.info;
				},
				function () {
					streamOptions.whileplaying();
				},
				true
			);

			scope.$watch(
				function () {
					return jplayerInterface.getTrackId();
				},
				function (id) {
					TracksResource.getWave({id: id}).$promise.then(function (data) {
						waveform.update({
							data: data.points
						});
					});
				}
			);
		};

	return {
		replace: true,
		restrict: 'AE',
		link: link,
		template: '<div class="waveform"></div>'
	};
});