(function(){
	'use strict';
  angular.module('CjClubUserApp').directive('playerUi', function(jplayerInterface, playlist) {
    return {
      restrict: 'EA',
      scope: true,
      controller: 'PlayerCtrl', //TODO перенсти в директиву
      templateUrl: 'views/main/includes/player.html',
//    templateUrl: function(el, attr) {
//      var tpl = attr.tpl ? attr.tpl : 'track_mini.html';
//      return 'views/main/includes/' + tpl;
//    },
      link: function(scope, elem) {
        $.each($(elem).find('.sound_control_slider'), function(count, item){
          //console.log(count);
          $(item).slider({
            animate:true,
            orientation: "vertical",
            range: "min",
            min:0,
            max:100,
            value:100,
            slide: function( event, ui ) {
              jplayerInterface.setVolume(ui.value / 100);
              $.each($('.sound_control_slider:not(:eq(' + count + '))'), function(c, i){
                $(i).slider('value', ui.value);
              });
            }
          });
        });
        scope.$watch(
          function() {
            return jplayerInterface.getVolume();
          },
          function(v) {
            $.each($(elem).find('.sound_control_slider'), function(count, item){
              $(item).slider('value', v * 100);
            });
          }
        );
        scope.togglePlaylist = function(){
          playlist.togglePlaylist();
        };
      }
    };
  });
})();