angular.module('CjClubUserApp').directive('mainHeader', function ($q, localStorageService, playlist) {
	'use strict';
	var
		deferred = $q.defer(),
		contentLoaded = deferred.promise,
		HEADER_STATE = 'cjclub_header_state',
		_currentState,
		headerState = {
			open: 'open',
			close: 'close'
		},
		scroller = function (element) {
			var
				top = document.documentElement.scrollTop || document.body.scrollTop,
				headerShadow = document.getElementById('header_shadow'),
				maxScroll = 250,
				headerShadowHeight = top ? ((top > maxScroll) ? 20 : 20 * top / maxScroll ) : 0;

			if (top) {
				element.addClass('fixed');
				//TODO убрать из этой директивы
				$('.promo_what').hide();
			} else {
				element.removeClass('fixed');
				$('.promo_what').show();
			}

			headerShadow.style.height = headerShadowHeight + "px";
			document.body.className = top === 0 ? 'onTop' : '';
		},
		setInitState = function (state) {
			contentLoaded.then(function () {
				var margin = state === headerState.close ? 82 : 150;
				$('#main-content').css('margin-top', margin);
			});

			changeState(state, 0);
		},
		changeState = function (state, duration) {
			_currentState = state;
			if (typeof duration === 'undefined') {
				duration = 300;
			}

			if (state === headerState.close) {
				$('#top').slideUp(duration);
				$('#topmini').slideDown();
				$('#header').animate({'height': '45'}, duration);
				$('.header-bg').animate({'height': '45'}, duration);
				$('#main-content').animate({'margin-top': '82'}, duration);
				$('.header_closer').hide();
				$('nav.cf').css('position', 'absolute').animate({'top': '45px'}, duration);
			} else {
				$('#topmini').slideUp(duration);
				$('#top').slideDown();
				$('#header').animate({'height': '113'}, duration);
				$('.header-bg').animate({'height': '150'}, duration);
				$('#main-content').animate({'margin-top': '150'}, duration);
				$('.header_closer').show();
				$('nav.cf').css('position', 'fixed').animate({'top': '113px'}, duration);
			}
			localStorageService.set(HEADER_STATE, state);
		},
		link = function (scope, element) {
			var state = localStorageService.get(HEADER_STATE) || headerState.open;
			setInitState(state);

			scope.togglePlaylist = function () {
				playlist.togglePlaylist();
			};

			scope.$on('$viewContentLoaded', function () {
				deferred.resolve();
				scroller(element);
			});

			scope.$on('header.changeState', function ($e, state) {
				playlist.close();
				changeState(state);
			});

			window.onscroll = function () {
				scroller(element);
			};
		};

	return {
		restrict: 'AE',
		scope: true,
		link: link,
		templateUrl: 'views/main/includes/main-header.html'
	};
});