App.controller('RootCtrl', function($rootScope, jplayerInterface) {
	$rootScope.player = jplayerInterface;
});
/*
 * установкой свойств этого сервиса выставляются статусные и ошибочные сообщения
 */
App.constant('Notifications', {
    error:  '',
    status: ''
});
/*
 * контроллер создает $scope где видны свойства сервиса Notifications
 */
App.controller('NotificationCtrl', function($scope, Notifications, $timeout) {
    $scope.Notifications = Notifications;
    $scope.$watch('Notifications.status', function() {
        $timeout(function() {
            $scope.Notifications.status = '';
        }, 3000);
    });
    $scope.$watch('Notifications.error', function() {
        $timeout(function() {
            $scope.Notifications.error = '';
        }, 3000);
    });
});