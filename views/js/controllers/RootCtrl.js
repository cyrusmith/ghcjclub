angular.module('CjClubUserApp').controller('RootCtrl', function($rootScope, jplayerInterface) {
	'use strict';
	$rootScope.player = jplayerInterface;
});
/*
 * установкой свойств этого сервиса выставляются статусные и ошибочные сообщения
 */
angular.module('CjClubUserApp').constant('Notifications', {
	error:  '',
	status: ''
});
/*
 * контроллер создает $scope где видны свойства сервиса Notifications
 */
angular.module('CjClubUserApp').controller('NotificationCtrl', function ($scope, Notifications, $timeout) {
	'use strict';
	$scope.Notifications = Notifications;
	$scope.$watch('Notifications.status', function () {
		$timeout(function () {
			$scope.Notifications.status = '';
		}, 3000);
	});
	$scope.$watch('Notifications.error', function () {
		$timeout(function () {
			$scope.Notifications.error = '';
		}, 3000);
	});
});