App.controller('BlogsDiscussedCtrl', function($scope, Blogs) {
	$scope.blogs = Blogs.query();
});