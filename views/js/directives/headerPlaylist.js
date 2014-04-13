(function () {
  'use strict';
  angular.module('CjClubUserApp').directive('headerPlaylist', function (playlist) {
    var
      toggle = function(elemtnt) {
        elemtnt.slideToggle(300);
      },
      link = function(scope, element) {
        scope.tracks = playlist.tracks;
        scope.close = function(){
          playlist.close();
        };

        scope.$on('playlist.toggle', function(){
          toggle(element);
        });
      };

    return {
      restrict: 'AE',
      replace: true,
      templateUrl: 'views/main/includes/headerPlaylist.html',
      link: link
    };
  });
})();