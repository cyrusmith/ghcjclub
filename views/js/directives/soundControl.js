angular.module('CjClubUserApp').directive('soundControl', function (jplayerInterface) {
	'use strict';
	var link = function (scope, element, attr) {
		var
			orientation = attr.orientation || 'vertical',
			soundControl = element.find('.volume-control');

		soundControl.slider({
			animate: true,
			orientation: orientation,
			range: "min",
			min: 0,
			max: 100,
			value: 100,
			slide: function (event, ui) {
				jplayerInterface.setVolume(ui.value / 100);
				soundControl.slider('value', ui.value);
				if (!scope.$$phase) {
					scope.value = ui.value;
					scope.$apply();
				}
			}
		});

		scope.$watch(
			function () {
				return jplayerInterface.getVolume();
			},
			function (v) {
				var value = v * 100;
				soundControl.slider('value', value);
				scope.value = value;
			}
		);

		scope.$on('$destroy', function () {
			soundControl.slider("destroy");
		});

		scope.toggleMute = function () {
			jplayerInterface.toggleMute();
		};

		scope.value = 100;
	};

	return {
		replace: true,
		restrict: 'AE',
		templateUrl: function (el, attr) {
			var tpl = attr.tpl ? attr.tpl : 'sound-control.html';
			return 'views/main/includes/' + tpl;
		},
		link: link
	};
});
