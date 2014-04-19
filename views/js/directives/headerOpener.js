angular.module('CjClubUserApp').directive('headerOpener', function ($rootScope) {
	'use strict';
	return {
		restrict: 'E',
		template: '<div class="header_opener"></div>',
		replace: true,
		link: function (scope, element) {
			element.on('click', function () {
				$rootScope.$broadcast('header.changeState', 'open');
			});
		}
	};
});