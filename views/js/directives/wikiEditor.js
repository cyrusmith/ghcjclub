App.directive('wikiEditor', function() {
    return {
        restrict: 'A',
        link: function(scope, element, attributes) {
            $(element).markItUp( wikiSettings );
        }
    }
});