App.directive('carousel', function() {
	return {
		strict: 'EA',
		link: function(scope, element, attributes) {
			scope.$watch(attributes.carouselWatchFor, function() {
				$(element).tinycarousel();
			});
		}
	}
});