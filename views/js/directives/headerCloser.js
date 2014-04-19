angular.module('CjClubUserApp').directive('headerCloser', function ($rootScope) {
	'use strict';
	return {
		restrict: 'E',
		template: '<div class="header_closer"></div>',
		replace: true,
		link: function (scope, element) {
			element.on('click', function () {
				$rootScope.$broadcast('header.changeState', 'close');
			});
		}
	};
});