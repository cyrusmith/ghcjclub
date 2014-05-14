angular.module('CjClubUserApp').factory('BackendUtils', function () {
    'use strict';

    function hash2query(obj, prefix) {
        var str = [];
        for (var p in obj) {
            var k = prefix ? prefix + "[" + p + "]" : p,
                v = obj[k];
            str.push(angular.isObject(v) ? hash2query(v, k) : (k) + "=" + encodeURIComponent(v));
        }
        return str.join("&").replace(/%20/g, "+");
    }

    return {
        transformRequestToForm: function (data, getHeaders) {
            var headers = getHeaders();
            headers["Content-Type"] = "application/x-www-form-urlencoded; charset=utf-8";
            return hash2query(data, null);
        }
    }
});