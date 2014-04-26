angular.module('CjClubUserApp').factory('playlist', function ($q, $http, $rootScope, localStorageService, TracksResource) {
	'use strict';
	var
		_trackIds,
		_isOpen = false,
		deferred = $q.defer(),
		promise = deferred.promise,
		tracks = [],
		PLAYLIST_KEY = 'cjclub_playlist',
		indexById = function (source, id) {
			var result = -1;

			source.some(function (item) {
				if (item.id === id) {
					result = source.indexOf(item);
					return true;
				}
				return false;
			});

			return result;
		},
		init = function () {
			_trackIds = localStorageService.get(PLAYLIST_KEY) || [];
			if (_trackIds.length > 0) {
				TracksResource.queryByIds({ids: _trackIds.toString()}).$promise.then(
					function (data) {
						if (data) {
							_trackIds.forEach(function (id) {
								var index = indexById(data, id);
								if (index !== -1) {
									tracks.push(data[index]);
								}
							});
						}
						deferred.resolve();
					},
					function () {
						deferred.resolve();
					}
				);
			} else {
				deferred.resolve();
			}
		},
	//TODO добавить возможность добавлять в плейлист с определенным именем
		add = function (track) {
			if (track && _trackIds.indexOf(track.id) === -1) {
				promise.then(function () {
					_trackIds.push(track.id);
					localStorageService.set(PLAYLIST_KEY, _trackIds);
					tracks.push(track);
				});
			}
		},
		contains = function (id) {
			return _trackIds.indexOf(id) !== -1;
		},
		remove = function (id) {
			var index = _trackIds.indexOf(id);
			if (index !== -1) {
				promise.then(function () {
					_trackIds.splice(index, 1);
					localStorageService.set(PLAYLIST_KEY, _trackIds);
					tracks.splice(index, 1);
				});
			}
		},
		hasPrev = function (id) {
			if (_trackIds.length > 0) {
				var index = _trackIds.indexOf(id);

				return index > 0;
			}
			// empty tracklist
			return false;
		},
		getPrev = function (id) {
			if (_trackIds.length > 0) {
				var index = _trackIds.indexOf(id);

				// playlist does not contain current track, start from the beginning
				if (index === -1) {
					return _trackIds[0];
				}

				if (index === 0) {
					return null;
				}

				return _trackIds[index - 1];
			}

			// empty tracklist
			return null;
		},
		hasNext = function (id, repeat) {
			if (_trackIds.length > 0) {
				var
					index = _trackIds.indexOf(id),
					next = index + 1;

				if (index === -1) {
					return false;
				}

				// current the last, if not "repeat" there are no tracks more
				if (next >= _trackIds.length) {
					return repeat ? true : false;
				}

				return true;
			}

			// empty tracklist
			return false;
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
			_trackIds.length = tracks.length = 0;
			localStorageService.remove(PLAYLIST_KEY);
		},
		togglePlaylist = function () {
			_isOpen = !_isOpen;
			$rootScope.$broadcast('playlist.toggle', _isOpen);
		},
		close = function () {
			if (_isOpen) {
				togglePlaylist();
			}
		},
		isEmpty = function () {
			return typeof _trackIds === 'undefined' || _trackIds.length === 0;
		},
		firstId = function () {
			return isEmpty() ? null : _trackIds[0];
		},
		changeOrder = function (current, newIndex) {
			if (current === newIndex) {
				return;
			}
			promise.then(function () {
				_trackIds.splice(newIndex, 0, _trackIds.splice(current, 1)[0]);
				tracks.splice(newIndex, 0, tracks.splice(current, 1)[0]);
				localStorageService.set(PLAYLIST_KEY, _trackIds);
			});
		},
		moveUp = function (index) {
			if (index === 0) {
				return;
			}
			changeOrder(index, index - 1);
		},
		moveDown = function (index) {
			if (index + 1 === _trackIds.length) {
				return;
			}
			changeOrder(index, index + 1);
		};

	init();

	return {
		add: add,
		remove: remove,
		contains: contains,
		clearPlaylist: clearPlaylist,
		hasNext: hasNext,
		hasPrev: hasPrev,
		getPrev: getPrev,
		getNext: getNext,
		tracks: tracks,
		togglePlaylist: togglePlaylist,
		close: close,
		firstId: firstId,
		isEmpty: isEmpty,
		changeOrder: changeOrder,
		moveUp: moveUp,
		moveDown: moveDown,
		promise: promise
	};
});