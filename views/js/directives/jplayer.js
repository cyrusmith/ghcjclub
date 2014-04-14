(function () {
  'use strict';
  angular.module('CjClubUserApp').directive('jplayer', function (jplayerInterface) {
    var defaultOptions = {
      autoNext: false
    };

    return {
      restrict: 'EA',
      replace: true,
      template: '<div id="jplayercontainer">Player is here</div>',
      link: function (scope, element, attrs) {
        var options = angular.extend({}, defaultOptions, attrs);
        jplayerInterface.init(element, options);

        scope.$on('$destroy', function () {
          jplayerInterface.destroy(element, options);
        });
      }
    };
  });
})();