/**
 * Created by Evgeny on 07.04.2014.
 */
(function(){
	'use strict';
	angular.module('CjClubUserApp').directive('addToPlaylist', function(playlist){
    var link = function(scope, element){
      element.on('click', function(){
        if(scope.track){
          playlist.add(scope.track);
        }
      });
    };

    return {
      strict: 'A',
      scope: {
        track: '=addToPlaylist'
      },
      link: link
    };
  });
})();