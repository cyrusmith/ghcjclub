angular.module('CjClubUserApp').controller('TracksCtrl', function ($scope, Styles, TracksResource) {
	'use strict';

	var
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
		};

	/* Сортровка по дате */
	$scope.sort = 'public_date';

	$scope.tracks = TracksResource.query();

	$scope.styles = Styles;
	$scope.styleFont = (function () {
		var
			minFont = 12,
			maxFont = 30,
			minCount = _.min(Styles, function (style) {
				return style.count;
			}).count,
			maxCount = _.max(Styles, function (style) {
				return style.count;
			}).count,
			u = uninterpolate(minCount, maxCount),
			i = interpolate(minFont, maxFont);
		return function (count) {
			return Math.floor(i(u(count)));
		};
	})();

	$scope.selectStyle = function(style) {
		$scope.tracks = TracksResource.query({style: style.name});
		$scope.selectedStyle = style;
		$scope.toggleStyles();
	};

	$scope.toggleStyles = function(){
		$scope.isStylesOpened = !$scope.isStylesOpened;
		tags.slideToggle(300);
	};
});

