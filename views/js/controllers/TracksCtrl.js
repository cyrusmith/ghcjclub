angular.module('CjClubUserApp').controller('TracksCtrl', function ($scope, Styles, TracksResource) {
	'use strict';

	var
		sortTypes = {
			byPit: 'pit',
			byDate: 'public_date'
		},
		periodTypes = {
			all: 'all',
			twoWeeks: '2week'
		},
		tags = $('#tags'),
		uninterpolate = function (a, b) {
			b = b - (a = +a) ? 1 / (b - a) : 0;
			return function (x) {
				return (x - a) * b;
			};
		},
		interpolate = function (a, b) {
			b -= a = +a;
			return function (t) {
				return a + b * t;
			};
		},
		getTracks = function () {
			var params = {};
			if ($scope.selectedStyle) {
				params.style = $scope.selectedStyle.id;
			}
			if ($scope.period === periodTypes.twoWeeks) {
				params.date = periodTypes.twoWeeks;
			}
			$scope.tracks = TracksResource.query(params);
		};

	/* Сортровка по дате */
	$scope.sortTypes = sortTypes;
	$scope.sort = sortTypes.byDate;
	/* За все время */
	$scope.periodTypes = periodTypes;
	$scope.period = periodTypes.all;
	$scope.$watch('period', function () {
		getTracks();
	});

	$scope.styles = Styles;
	$scope.styleFont = (function () {
		var
			promise = Styles.$promise,
			minFont = 12,
			maxFont = 30,
			minCount, maxCount, u, i;

		promise.then(function (styles) {
			minCount = _.min(styles, function (style) {
				return style.tracks;
			}).tracks;
			maxCount = _.max(styles, function (style) {
				return style.tracks;
			}).tracks;
			u = uninterpolate(minCount, maxCount);
			i = interpolate(minFont, maxFont);
		});
		return function (count) {
			return Math.floor(i(u(count)));
		};
	})();

	$scope.selectStyle = function (style) {
		$scope.selectedStyle = style;
		$scope.toggleStyles();
		getTracks();
	};

	$scope.toggleStyles = function () {
		$scope.isStylesOpened = !$scope.isStylesOpened;
		tags.slideToggle(300);
	};
});

