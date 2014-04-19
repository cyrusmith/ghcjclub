angular.module('CjClubUserApp').directive('trackMini', function () {
	'use strict';
	return {
		restrict: 'EA',
		templateUrl: function (el, context) {
			var tpl = context.tpl ? context.tpl : 'track_mini.html';
			return 'views/main/includes/' + tpl;
		}
	};
});