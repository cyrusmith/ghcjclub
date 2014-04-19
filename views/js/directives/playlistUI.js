angular.module('CjClubUserApp').directive('playlistUi', function (playlist, jplayerInterface) {
	'use strict';
	var
		slide = function (element, scope) {
			var
				value,
				scrollContainer = element.find('.playlist_content'),
				scrollList = element.find('.playlist_list'),
				delta = scrollList.height() - scrollContainer.height(),
				wheel = true,
				itemHeight = 51,
				dH = 100 / (delta / itemHeight);

			function slideHandle(e, ui) {
				scrollContainer.stop(true, true).animate({"scrollTop": (1 - ui.value / 100) * delta });
			}

			if (delta > 0) {
				scope.scrollSlider.show().off('slide').on('slide', slideHandle);

				scrollContainer.unmousewheel().mousewheel(function (e, d) {
					if (!wheel) {
						return false;
					}
					wheel = false;

					value = scope.scrollSlider.slider('option', 'value');

					if (d > 0) {
						value += dH;
					}
					else if (d < 0) {
						value -= dH;
					}

					value = Math.max(0, Math.min(100, value));
					scope.scrollSlider.slider('value', value);
					scrollContainer.stop(true, false).animate({"scrollTop": (1 - value / 100) * delta }, function () {
						wheel = true;
					});
					e.preventDefault();
				});

				if (scope.prevDelta) {
					value = scope.scrollSlider.slider('option', 'value');
					value = (value * delta) / scope.prevDelta;
					value = Math.max(0, Math.min(100, value));
					scope.scrollSlider.slider('value', value);
				}

			} else {
				scope.scrollSlider.hide();
			}

			scope.prevDelta = delta;
		},
		toggle = function (elemtnt, scrollSlider, isOpen) {
			elemtnt.slideToggle(300, function () {
				if (isOpen) {
					slide(elemtnt, scrollSlider);
				}
			});
		},
		link = function (scope, element) {
			var sortableList = element.find('.playlist_list');
			sortableList.sortable({
				handle: '.draggable',
				start: function (e, ui) {
					ui.item.data('start', ui.item.index());
				},
				stop: function (e, ui) {
					var
						start = ui.item.data('start'),
						end = ui.item.index();

					playlist.changeOrder(start, end);

					scope.$apply();
				}
			});

			scope.scrollSlider = element.find('.playlist_scroll');
			scope.scrollSlider.slider({
				animate: true,
				orientation: "vertical",
				range: "min",
				min: 0,
				max: 100,
				value: 100
			});

			scope.tracks = playlist.tracks;
			scope.close = function () {
				playlist.close();
			};

			scope.playIdOrPause = jplayerInterface.playIdOrPause;
			scope.isIdbeingPlayed = jplayerInterface.isIdbeingPlayed;

			scope.remove = playlist.remove;

			scope.up = playlist.moveUp;
			scope.down = playlist.moveDown;

			scope.$on('playlist.toggle', function ($e, state) {
				toggle(element, scope, state);
			});

			scope.$watch(function () {
				return playlist.tracks.length;
			}, function () {
				slide(element, scope);
				sortableList.sortable("refresh");
			});

			scope.$on('$destroy', function () {
				scope.scrollSlider.slider('destroy');
				sortableList.sortable("destroy");
			});
		};

	return {
		restrict: 'AE',
		replace: true,
		templateUrl: 'views/main/includes/playlist.html',
		link: link
	};
});