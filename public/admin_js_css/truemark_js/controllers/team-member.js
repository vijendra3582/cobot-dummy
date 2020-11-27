angular.module('truemarkApp').controller('teamMemberController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'TeamMemberDetail', 'Upload', '$filter', function ($http, myConfig, $location, $routeParams, $scope, $timeout, TeamMemberDetail, Upload, $filter) {
    $scope.dataFrm = {};
    $scope.required = {
        "image": true
    };

    $scope.dataFrm.status = 1;
    $scope.submitProcess = 0;
    $scope.dataFrmError = {};
    $scope.dataFrm.id = 0;
    $scope.addTitle = 'Add Team Member';
    $scope.fullPageLoader = 0;
    $scope.toTimestamp = function (date) {
        return new Date(date).getTime();
    };

    var guid = function () {
        var s4 = function () {
            return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
        }
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
            s4() + '-' + s4() + s4() + s4();
    }

    /* If Click On Edit */
    if ($routeParams.id) {
        $scope.dataFrm.id = $routeParams.id;
        $scope.fullPageLoader = 1;
        $scope.addTitle = 'Modify Team Member';

        /* Get Single Team Member Data */
        var dataToSend = {};
        dataToSend.call = 'teamMemberDetail';
        dataToSend.stype = 'getData';
        dataToSend.id = $routeParams.id;

        var promise = $http({
            method: 'POST',
            url: 'cms/team/edit',
            data: dataToSend
        });

        promise.then(
            function (payload) {
                if (parseInt(payload.data.length) > parseInt(0)) {
                    $scope.dataFrm = payload.data.data;
                    $scope.dataFrm.status = payload.data.data.status == 0 ? false : true;

                    $scope.dataFrm.image = '';
                    if (payload.data.data.image_disp) {
                        $scope.fuploading = 2;
                        $scope.required.image = false;
                    } else {
                        $scope.dataFrm.image_disp = '';
                        $scope.fuploading = 0;
                        $scope.required.image = true;
                    }

                    $timeout(function () {
                        $scope.fullPageLoader = 0;
                    }, 1000);
                } else {
                    $location.path('/welcome');
                }
            },
            function (errorPayload) {
                console.log('failure loading team', errorPayload);
            }
        );

    } else {
        /* If Click On Create Team */
        $scope.dataFrm = {};
        $scope.dataFrm.image = '';
        $scope.dataFrm.image_disp = '';
        $scope.dataFrm.id = 0;
        $scope.dataFrm.status = true;
    }

    /* Upload Image */
    $scope.fuploading = 0;
    $scope.upload = function (file) {
        $scope.fuploading = 1;
        var uuid = guid();
        Upload.upload({
            url: 'cms/chunk_upload',
            resumeChunkSize: '2MB',
            data: {
                filename: file.name,
                file: file,
                uuid: uuid
            }
        }).then(function (resp) {
            if (resp.data.SUCCESS == "1") {
                $scope.dataFrm.image = resp.data.IMAGE_NAME;
                $scope.dataFrm.image_disp = resp.data.PATH_TO_IMAGE;
                $scope.fuploading = 2;
                $scope.required.image = false;
            } else {
                $scope.fileuploaderror = resp.data.MSG;
                $scope.fuploading = 3;
                $scope.required.image = true;
                $timeout(function () {
                    $scope.fuploading = 0;
                }, 3000);

            }
        }, function (resp) {
            console.log('Error status: ' + resp.status);
        }, function (evt) {
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            $scope.fuploading = 1;
        });
    };

    /* Submit Form */
    $scope.submitProcess = 0;
    $scope.submitProcessMsg = '';
    $scope.submit = function () {
        $scope.submitProcess = 1;
        $scope.dataFrm.call = 'team';
        $scope.dataFrm.stype = 'saveTeam';
        $scope.dataFrmError = {};
        $http({
            method: 'POST',
            url: 'cms/team/save',
            data: $scope.dataFrm
        }).then(function (response) {
            $scope.submitProcess = 2;

            if (response.data.SUCCESS == '1') {
                swal({
                    title: "Success",
                    text: "Successfully Saved.",
                    icon: "success",
                    timer: 3000
                });
                $scope.submitProcessMsg = '&#x2714; Successfully Saved';
            } else if (response.data.SUCCESS == '2') {
                $scope.submitProcessMsg = '&#x2757; Already Exists';
            } else {
                $scope.submitProcessMsg = '&#x2718; Sorry Cannot Process Your Request';
            }

            $timeout(function () {
                $scope.submitProcess = 0;
                if (response.data.SUCCESS == '1') {
                    $location.path("/listTeam/1");
                    $scope.dataFrm = {};
                }
            }, 3000);

        }, function (err) {
            $scope.submitProcess = 0;
            if (err.status == 422) {
                swal({
                    title: "Validation Error",
                    text: "Please check data !",
                    icon: "error",
                    timer: 1000
                });
                var errors = err.data.errors;
                $scope.dataFrmError = errors;
                $("html,body").animate({
                    scrollTop: $(".team-" + Object.keys($scope.dataFrmError)[0]).offset().top - ($("header").height() + 10)
                }, 1000);
            }
        });
    };

    /* Remove Image */
    $scope.removeImage = function (field, $event) {
        $event.preventDefault();
        var c = confirm("Are you sure you wish to remove?");
        if (c) {
            if ($routeParams.id) {
                $http({
                    method: 'POST',
                    url: 'cms/team/remove-image',
                    data: {
                        call: 'team',
                        stype: 'removeImage',
                        field: field,
                        id: $routeParams.id
                    }
                }).success(function (response) {
                    if (response.SUCCESS == 1) {
                        $scope.fuploading = 0;
                        $scope.dataFrm.image = '';
                        $scope.dataFrm.image_disp = '';
                        $scope.required.image = true;
                    } else {
                        $scope.fuploading = 2;
                        $scope.required.image = false;
                    }
                });
            } else {
                $scope.fuploading = 0;
                $scope.dataFrm.image = '';
                $scope.dataFrm.image_disp = '';
            }
        }
    };

    /* Ckeditor Options */
    $scope.options = {
        language: 'en',
        allowedContent: true,
        entities: false,
        enterMode: 2,
        extraPlugins: 'divarea,popupinsert',
        filebrowserBrowseUrl: 'admin_js_css/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl: 'admin_js_css/ckfinder/ckfinder.html?type=Images',
        filebrowserFlashBrowseUrl: 'admin_js_css/ckfinder/ckfinder.html?type=Flash',
        filebrowserUploadUrl: 'admin_js_css/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl: 'admin_js_css/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl: 'admin_js_css/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    };

    /* Start Ckeditor */
    $scope.onReady = function () {

    };
}]);
