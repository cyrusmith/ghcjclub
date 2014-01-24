App.controller('NewsColumnCtrl', function($scope, Blogs) {
	$scope.newsCollection = Blogs.query();
});