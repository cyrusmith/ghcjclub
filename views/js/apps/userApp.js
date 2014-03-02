var App = angular.module('CjClubUserApp', ['ngResource', 'ngRoute']);
App.config(function($routeProvider) {
	$routeProvider.
		when('/news', {
			templateUrl: 'views/main/articles.html',
			controller: ['$scope', 'Articles', function($scope, Articles) {
				$scope.articles = Articles;
			}],
			resolve: {
				Articles: function(Blogs) {return Blogs.query(); }
			}
		}).
		when('/news/:id', {
			templateUrl: 'views/main/article.html',
			controller: ['$scope', 'Article', function($scope, Article) {
				$scope.article = Article;
			}],
			resolve: {
				Article: function(Blogs, $route) {
					return Blogs.get({id: $route.current.params.id});
				}
			}
		}).
		when('/tracks', {
			templateUrl: 'views/main/tracks.html',
			controller: ['$scope', 'Tracks', function($scope, Tracks) {
				$scope.tracks = Tracks;
			}],
			resolve: {
				Tracks: function(TracksResource) {return TracksResource.query(); }
			}
		}).
		when('/tracks/:id', {
			templateUrl: 'views/main/track.html',
			controller: 'TrackCtrl',
			resolve: {
				Track: function(TracksResource, $route, $q) {
					return TracksResource.get({id: $route.current.params.id}).$promise;
				}
			}
		}).
		when('/about', {
			templateUrl: 'views/main/textpage.html',
			controller: function($scope, Content) {
				$scope.content = Content;
			},
			resolve: {
				Content: function() {
					return {
						title: 'О проекте',
						text: 'some text about'
					};
				}
			}
		}).
		when('/projects', {
			templateUrl: 'views/main/projects.html',
			controller: ['$scope', 'Projects', function($scope, Projects) {
				$scope.projects = Projects;
			}],
			resolve: {
				Projects: function(Projects) {return Projects.query(); }
			}
		}).
		when('/projects/:id', {
			templateUrl: 'views/main/project.html',
			controller: ['$scope', 'Project', 'Albums', 'Tracks', 'Blogs', function($scope, Project, Albums, Tracks, Blogs) {
				$scope.project = Project;
				$scope.project.$promise.then(function() {
					$scope.project.styles = [
						{
							id: 2,
							style: 'Trance'
						},
						{
							id: 14,
							style: 'DnB'
						}
					];
				}); // todo mockup
				$scope.blog = Blogs;
				$scope.tracks = Tracks;
				$scope.albums = Albums;
				$scope.isAccessGranted = false; // доступ к изменению
			}],
			resolve: {
				Project: function(Projects, $route) {
					return Projects.get({id: $route.current.params.id});
				},
				Albums: function(Albums, $route) {
					return Albums.query({projectId: $route.current.params.id});
				},
				Tracks: function(TracksResource, $route) {
					return TracksResource.query({projectId: $route.current.params.id});
				},
				Blogs: function(Blogs, $route) {
					return Blogs.query({projectId: $route.current.params.id});
				}
			}
		}).
		otherwise({
			redirectTo: '/',
			templateUrl: 'views/main/index.html'
		});
});