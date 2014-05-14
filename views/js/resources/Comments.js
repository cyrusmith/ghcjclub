App.factory('Comments', function($resource) {
    return $resource('comments/:objectType/:objectId', {}, {
    });
});