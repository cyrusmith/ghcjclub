App.directive('comments', ['$log', 'SignedUser', function ($log, SignedUser) {

    function CommentModel(data) {
        if (angular.isObject(data)) {
            this.setData(data);
        }
        this.__children = [];
        this.__parent = null;
    }

    CommentModel.commentsSortByDateAsc = function (a, b) {
        var aDate = moment(a.data.datewritten, "YYYY-MM-DD HH:mm:ss"),
            bDate = moment(b.data.datewritten, "YYYY-MM-DD HH:mm:ss");
        return aDate.unix() - bDate.unix();
    };

    CommentModel.prototype.setData = function (data) {
        this.data = data;
        this.__visible = this.data.status == "active" && !this.data.complaint;
        this.__mine = SignedUser.isSigned() && SignedUser.user.id == this.data.author.id;
    }

    CommentModel.prototype.isVisible = function () {
        return this.__visible;
    }

    CommentModel.prototype.isMine = function () {
        return this.__mine;
    }

    CommentModel.prototype.setVisibility = function (isVisible) {
        this.__visible = !!isVisible;
    }

    CommentModel.prototype.rate = function (dir) {
        $log.log("rate:", dir);
        this.data.rating = this.data.rating || 0;
        this.data.rating += dir;
    }

    CommentModel.prototype.addChild = function (child) {
        this.__children.push(child);
        this.__children.sort(CommentModel.commentsSortByDateAsc);
    }

    CommentModel.prototype.setParent = function (parent) {
        this.__parent = parent;
        this.__parent.addChild(this);
    }

    CommentModel.prototype.getParent = function () {
        return this.__parent;
    }

    CommentModel.prototype.getChildren = function () {
        return this.__children;
    }

    return {
        restrict: "A",
        templateUrl: "views/main/includes/comments.html",
        scope: {

            /**
             * Type of resource to which these comments are being added
             * Currently supported values are: "article", "track"
             */
            objectType: "@",

            /**
             * Resource identifier
             */
            objectId: "@"

        },
        controller: ['$scope', '$element', '$attrs', '$http', '$sce', 'Comments', 'BackendUtils', function ($scope, $element, $attrs, $http, $sce, Comments, BackendUtils) {

            $log.log("Comments:", $scope);

            function getTree(result) {
                var hash = {};
                for (var i = 0; i < result.length; i++) {
                    result[i].message = $sce.trustAsHtml(result[i].message);
                    var model;
                    if (!hash.hasOwnProperty(result[i].id)) {
                        model = new CommentModel(result[i]);
                    }
                    else {
                        model = hash[result[i].id];
                        model.setData(result[i]);
                    }

                    if (result[i].com_to_com > 0) {
                        var parent = null;
                        if (hash.hasOwnProperty(result[i].com_to_com)) {
                            parent = hash[result[i].com_to_com];
                        }
                        else {
                            parent = new CommentModel();
                            hash[result[i].com_to_com] = parent;
                        }
                        parent.addChild(model);
                    }
                    else {
                        $scope.comments.push(model);
                        $scope.comments.sort(CommentModel.commentsSortByDateAsc);
                    }

                    hash[result[i].id] = model;

                }
                return hash;
            }

            $scope.isSignedUser = SignedUser.isSigned();
            $scope.user = SignedUser.user;

            $scope.comments = [];
            var commentsHash = {};
            Comments.query({objectType: $scope.objectType, objectId: $scope.objectId}).$promise.then(function (result) {
                $log.log(result);

                var hash = {};
                for (var i = 0; i < result.length; i++) {
                    var model;
                    if (!hash.hasOwnProperty(result[i].id)) {
                        model = new CommentModel(result[i]);
                    }
                    else {
                        model = hash[result[i].id];
                        model.setData(result[i]);
                    }

                    if (result[i].com_to_com > 0) {
                        var parent = null;
                        if (hash.hasOwnProperty(result[i].com_to_com)) {
                            parent = hash[result[i].com_to_com];
                        }
                        else {
                            parent = new CommentModel();
                            hash[result[i].com_to_com] = parent;
                        }
                        model.setParent(parent);
                    }

                    $scope.comments.push(model);
                    $scope.comments.sort(CommentModel.commentsSortByDateAsc);

                    hash[result[i].id] = model;

                }
                commentsHash = hash;
                $log.log($scope.comments);
            });

            $scope.responding = {};

            $scope.edit = function (comment) {
                $log.log("edit:", comment);
            }

            $scope.delete = function (comment) {
                $log.log("delete:", comment);
            }

            $scope.submit = function (parentComment) {

                $log.log("Submit:", parentComment, $scope.newcomment);

                var message = parentComment == null ? $scope.newcommentText : $scope.responseText;

                $http({
                    method: 'post',
                    url: '/comments/' + $scope.objectType + '/' + $scope.objectId,
                    data: {
                        authorId: "42491",
                        message: message,
                        object_type: $scope.objectType,
                        object_id: $scope.objectId,
                        track_sharing: false //TODO
                    },
                    transformRequest: BackendUtils.transformRequestToForm
                }).success(function () {
                        alert('Ok!');
                    }).error(function () {
                        alert('Error!');
                    });

                $scope.newcommentText = "";
                $scope.responseText = "";
                if (parentComment != null) {
                    $scope.responding = {};
                }
            }

            $scope.response = function (comment, isPrivateResponce) {
                $log.log("response:", comment, isPrivateResponce);
                $scope.responding = {};
                $scope.responding[comment.data.id] = true;
            }

            $scope.addToFriends = function (user) {
                $log.log("addToFriends:", user);
            }

            $scope.ignore = function (user) {
                $log.log("ignore:", user);
            }

            $scope.complain = function (comment) {
                $log.log("complain:", comment);
            }

            function handleCtrlEnter(e) {
                if (!e.ctrlKey || e.keyCode != 13) return;
                var focusedEl = $(document.activeElement),
                    id = focusedEl.attr('id');

                if (!id) {
                    return;
                }

                if ("newcomment-textarea" == id) {
                    $scope.submit(null);
                    focusedEl.val('');
                }
                else if (id.indexOf("response-textarea-") !== -1) {
                    var matches = id.match(/^response-textarea-([0-9]*)$/);
                    if (matches) {
                        $scope.submit(commentsHash[matches[1]])
                        focusedEl.val('');
                    }
                }
                $scope.$apply();

            }

            $(document).on('keydown', handleCtrlEnter);

            $element.on('$destroy', function () {
                $log.log("Destroy comments");
                $(document).off('keydown', handleCtrlEnter);
            });

        }],

        link: function (scope, element, attrs) {

        }

    }

}]);