(function () {
  'use strict';
  angular.module('CjClubUserApp').factory('playlist', function ($q, $http, $rootScope, localStorageService) {
    var
      _trackIds,
      _isOpen = false,
      deferred = $q.defer(),
      promise = deferred.promise,
      tracks = [],
      PLAYLIST_KEY = 'cjclub_playlist',
      init = function () {
        _trackIds = localStorageService.get(PLAYLIST_KEY) || [];
        if (_trackIds.length > 0) {
          $http({
            method: 'GET',
            url: 'tracks?id=' + _trackIds.toString()
          })
            .success(function (data) {
              if (data) {
                data.forEach(function (track) {
                  tracks.push(track);
                });
              }
              deferred.resolve();
            });
        } else {
          deferred.resolve();
        }
      },
    //TODO добавить возможность добавлять в плейлист с определенным именем
      addToPlaylist = function (track) {
        if (track && _trackIds.indexOf(track.id) === -1) {
          _trackIds.push(track.id);
          localStorageService.set(PLAYLIST_KEY, _trackIds);
          promise.then(function () {
            tracks.push(track);
          });
        }
      },
      getNext = function (id, repeat) {
        if (_trackIds.length > 0) {
          var
            index = _trackIds.indexOf(id),
            next = index + 1;

          // playlist does not contain current track, start from the beginning
          if (index === -1) {
            return _trackIds[0];
          }

          // current the last, if not "repeat" there are no tracks more
          if (next >= _trackIds.length) {
            return repeat ? _trackIds[0] : null;
          }

          return _trackIds[next];
        }

        // empty tracklist
        return null;
      },
      clearPlaylist = function () {
        _trackIds.length = 0;
        localStorageService.remove(PLAYLIST_KEY);
      },
      togglePlaylist = function () {
        $rootScope.$broadcast('playlist.toggle');
        _isOpen = !_isOpen;
      },
      close = function () {
        if (_isOpen) {
          togglePlaylist();
        }
      };

    init();

    return {
      add: addToPlaylist,
      clearPlaylist: clearPlaylist,
      getNext: getNext,
      tracks: tracks,
      togglePlaylist: togglePlaylist,
      close: close
    };
  });
})();