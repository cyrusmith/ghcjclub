App.directive('jplayer', function(jplayerInterface) {
	return {
		strict: 'EA',
		template: '<div id="jplayercontainer">Player is here</div>',
		link: function(scope, element) {
			jplayerInterface.init($(element));
		}
	}
});