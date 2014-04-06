(function () {
  'use strict';
  angular.module('CjClubUserApp').factory('playlist', function (localStorageService) {
    var
      playlist,
      PLAYLIST_KEY = 'cjclub_playlist',
      addToPlaylist = function (id) {
        if(playlist.indexOf(id) === -1) {
          playlist.push(id);
          localStorageService.set(PLAYLIST_KEY, playlist);
        }
      },
      getNext = function (id, repeat) {
        if(playlist.length > 0){
          var
            index = playlist.indexOf(id),
            next = index + 1;

          // playlist does not contain current track, start from the beginning
          if(index === -1) {
            return playlist[0];
          }

          // current the last, if not "repeat" there are no tracks more
          if(next >= playlist.length) {
            return repeat ? playlist[0] : null;
          }

          return playlist[next];
        }

        // empty tracklist
        return null;
      },
      clearPlaylist = function(){
        playlist.length = 0;
        localStorageService.remove(PLAYLIST_KEY);
      };

    playlist = localStorageService.get(PLAYLIST_KEY) || [];

    return {
      add: addToPlaylist,
      clearPlaylist: clearPlaylist,
      getNext: getNext,
      list: playlist
    };
  });
})();