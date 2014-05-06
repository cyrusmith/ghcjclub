angular.module('CjClubUserApp').directive('trackMini', function (playlist) {
	'use strict';
	return {
		restrict: 'EA',
		link: function (scope) {
			scope.playlist = playlist;
		},
		templateUrl: function (el, context) {
			var tpl = context.tpl ? context.tpl : 'track_mini.html';
			return 'views/main/includes/' + tpl;
		}
	};
});