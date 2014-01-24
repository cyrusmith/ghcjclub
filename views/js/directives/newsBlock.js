App.directive('newsBlock', function() {
	return {
		restrict: 'E',
		templateUrl: 'views/main/includes/newsBlock.html',
		controller: 'NewsColumnCtrl',
		link: function () {
		}
	}
});