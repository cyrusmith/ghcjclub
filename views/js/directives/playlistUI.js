angular.module('CjClubUserApp').directive('playlistUi', function (playlist, jplayerInterface) {
	'use strict';
	var
		itemHeight = 51,
		slide = function (scope) {
			var
				value,
				delta = scope.scrollList.height() - scope.scrollContainer.height(),
				wheel = true,
				dH = 100 / (delta / itemHeight);

			function slideHandle(e, ui) {
				scope.scrollContainer.stop(true, true).animate({"scrollTop": (1 - ui.value / 100) * delta });
			}

			if (delta > 0) {
				scope.scrollSlider.show().off('slide').on('slide', slideHandle);

				scope.scrollContainer.unmousewheel().mousewheel(function (e, d) {
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
					scope.scrollContainer.stop(true, false).animate({"scrollTop": (1 - value / 100) * delta }, function () {
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
		scroll = function (scope, top) {
			if (scope.scrollInterval) {
				return;
			}

			var
				delta = scope.scrollList.height() - scope.scrollContainer.height(),
				dH = 100 / (delta / itemHeight);

			scope.scrollInterval = setInterval(function () {
				var
					value = scope.scrollSlider.slider('option', 'value'),
					initial = value;

				if (top) {
					value += dH;
				}
				else {
					value -= dH;
				}
				if (initial !== value) {
					value = Math.max(0, Math.min(100, value));
					scope.scrollSlider.slider('value', value);
					scope.scrollContainer.stop(true, false).animate({"scrollTop": (1 - value / 100) * delta });
				}
			}, 300);
		},
		initSortable = function (scope, element){
			var sortableList = element.find('.playlist_list');
			sortableList.sortable({
				handle: '.draggable',
				start: function (e, ui) {
					ui.item.data('start', ui.item.index());
				},
				stop: function (e, ui) {
					clearInterval(scope.scrollInterval);
					var
						start = ui.item.data('start'),
						end = ui.item.index();

					playlist.changeOrder(start, end);

					scope.$apply();
				},
				change: function ($e, ui) {
					if (ui.position.top - scope.scrollContainer.scrollTop() < 0 ) {
						scroll(scope, true);
					}
					else if (ui.position.top - scope.scrollContainer.scrollTop() > scope.scrollContainer.height() ) {
						scroll(scope, false);
					} else {
						clearInterval(scope.scrollInterval);
						scope.scrollInterval = 0;
					}
				}
			});

			return sortableList;
		},
		toggle = function (elemtnt, scope, isOpen) {
			elemtnt.slideToggle(300, function () {
				if (isOpen) {
					slide(scope);
				}
			});
		},
		link = function (scope, element) {
			scope.scrollInterval = 0;
			scope.scrollContainer = element.find('.playlist_content');
			scope.scrollList = element.find('.playlist_list');
			scope.scrollSlider = element.find('.playlist_scroll');
			scope.scrollSlider.slider({
				animate: true,
				orientation: "vertical",
				range: "min",
				min: 0,
				max: 100,
				value: 100
			});

			var sortableList = initSortable(scope, element);

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
				slide(scope);
				sortableList.sortable("refresh");
			});

			scope.$on('$destroy', function () {
				scope.scrollSlider.slider('destroy');
				sortableList.sortable("destroy");
				clearInterval(scope.scrollInterval);
			});
		};

	return {
		restrict: 'AE',
		replace: true,
		templateUrl: 'views/main/includes/playlist.html',
		link: link
	};
});