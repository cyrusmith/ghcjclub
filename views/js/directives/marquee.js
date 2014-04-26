angular.module('CjClubUserApp').directive('marquee', function ($interpolate) {
	'use strict';
	return {
		restrict: 'A',
		compile: function (tElem) {
			var interpolateFn = $interpolate(tElem.html(), true);
			return function (scope, element) {
				var child = element.children();
				scope.$watch(interpolateFn, function () {
					if (element.width() < child.width()) {
						child.addClass('marquee');
					} else {
						child.removeClass('marquee');
					}
				});
			};
		}
	};
});