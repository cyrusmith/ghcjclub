/*
 * глобальный котнроллер, доступный их всех скопов
 */
App.controller('RootCtrl', function($rootScope, $location, $http, Notifications, $q) {
	/*
	 * глобальный метод для перехода по ссылке, ссылка относительна текущего пути, а не base href,
	 * поэтому не надо мучиться указанием пути
	 * @example
	 * <a href="some/url/#newpage">
	 *
	 * <a ng-click="go('/#newpage')">
	 */
	$rootScope.go = function(path, replace) {
		$location.path(path);
		if (replace) {
			$location.replace();
		}
	};

	$rootScope.loading = false; // глобальный индикатор загрузки
	$rootScope.setLoading = function(value) {
		$rootScope.loading = value;
	};

    $rootScope.submitModel = function(url, model, formController) {
        console.log('sybmitting ', model);
        if (!model) model = {};
        /* reset all serverside error have set before */
        if (formController) {
            angular.forEach(formController.$error.serverside, function(el) {
                el.$setValidity('serverside', true);
            });
        }
        Notifications.error = '';
        $rootScope.setLoading(true);
        return $http.post(url, model)
            .catch(function(response) {
                var errorsToShowGlobally = [];
                if (angular.isObject(response.data)) {
                    /*
                     * если сообщение об ошибке - хэш, то раскидать по элементам
                     */
                    angular.forEach(response.data, function(value, key) {
                        if (formController && formController.hasOwnProperty(key)) {
                            formController[key].$setValidity('serverside', false);
                            if (formController.serversideErrors == undefined) {
                                formController.serversideErrors = {};
                            }
                            formController.serversideErrors[key] = value.join('; ');
                        } else {
                            errorsToShowGlobally.push(value.join('; '));
                        }
                    });
                } else {
                    /*
                     * иначе вывести как есть
                     */
                    errorsToShowGlobally = response;
                }
                if (errorsToShowGlobally.length)
                    Notifications.error = errorsToShowGlobally;
                return $q.reject(response);
            })
            .finally(function() {
                $rootScope.setLoading(false);
            });
    };
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