App.factory('Comments', function($resource) {
    return $resource('comments/:resourceType/:id', {}, {
    });
});