App.controller('ArticlesColumnCtrl', function($scope, Blogs) {
	$scope.articles = Blogs.query();
});