App.directive('jplayer', function(jplayerInterface) {
  'use strict';
  var defaultOptions = {
    autoNext: false
  };

	return {
    restrict: 'EA',
		template: '<div id="jplayercontainer">Player is here</div>',
		link: function(scope, element, attrs) {
      var options = angular.extend({}, defaultOptions, attrs);
			jplayerInterface.init(element, options);

      scope.$on('$destroy', function(){
        jplayerInterface.destroy(element, options);
      });
		}
	};
});