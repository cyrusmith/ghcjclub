(function () {
  'use strict';
  angular.module('CjClubUserApp').directive('headerOpener', function ($rootScope) {
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
})();