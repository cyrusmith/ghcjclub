(function () {
  'use strict';
  angular.module('CjClubUserApp').directive('mainHeader', function ($rootScope, localStorageService) {
    var
      HEADER_STATE = 'cjclub_header_state',
      headerState = {
        open: 'open',
        close: 'close'
      },
      setInitState = function (state) {
        $rootScope.$on('$viewContentLoaded', function() {
          var margin = state === headerState.close ? 82 : 150;
          $('#c').css('margin-top', margin);
        });

        changeState(state, 0);
      },
      changeState = function (state, duration) {
        if(typeof duration === 'undefined') {
          duration = 300;
        }

        if (state === headerState.close) {
          $('#top').slideUp(duration);
          $('#topmini').slideDown();
          $('#header').animate({'height': '45'}, duration);
          $('.header-bg').animate({'height': '45'}, duration);
          $('#c').animate({'margin-top': '82'}, duration);
          $('.header_closer').hide();
          $('nav.cf').css('position', 'absolute').animate({'top': '45px'}, duration);
        } else {
          $('#topmini').slideUp(duration);
          $('#top').slideDown();
          $('#header').animate({'height': '113'}, duration);
          $('.header-bg').animate({'height': '150'}, duration);
          $('#c').animate({'margin-top': '150'}, duration);
          $('.header_closer').show();
          $('nav.cf').css('position', 'fixed').animate({'top': '113px'}, duration);
        }
        localStorageService.set(HEADER_STATE, state);
      },
      link = function (scope, element) {
        var state = localStorageService.get(HEADER_STATE) || headerState.open;
        setInitState(state);

        scope.$on('header.changeState', function($e, state){
          changeState(state);
        });
      };

    return {
      restrict: 'AE',
      scope: true,
      link: link
    };
  });
})();