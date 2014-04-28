App.controller('ArticleEditCtrl', function ($scope, $http, Notifications) {

    $scope.announcement = false;

    $scope.article = {};

    $scope.setAnnouncement = function (event) {
        event.preventDefault();
        $scope.announcement = true;
    }

    $scope.save = function (event) {
        event.preventDefault();
        $http.post('/blogs', $scope.article);
    }
});