App.controller('AuthCtrl', function($scope, $window, $http, Notifications) {
    $scope.logoutPrompt = function(msg) {
        if (!$window.confirm(msg)) return;
        $http.post('CjclubUser/doLogout', {}).then(function() {
            $window.location.reload();
        });
    };
    $scope.authData = {
        login: '',
        password: '',
        save: false
    };
    $scope.login = function() {
        $http.post('CjclubUser/doLogin', $scope.authData).then(function() {
            $window.location.reload();
        }, function(error) {
            Notifications.error = error.data;
        });
    }
});