App.directive('playerUi', function(jplayerInterface) {
	return {
		restrict: 'EA',
		scope: true,
		controller: 'PlayerCtrl',
		templateUrl: 'views/main/includes/player.html',
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
		}
	}
});