App.directive('simpleHtml', function() {
	return function(scope, element, attr) {
		scope.$watch(attr.simpleHtml, function (value) {
			element.html(scope.$eval(attr.simpleHtml));
		})
	};
})