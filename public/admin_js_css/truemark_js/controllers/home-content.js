angular.module('truemarkApp').controller('homeContentController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'Upload', function ($http, myConfig, $location, $routeParams, $scope, $timeout, Upload) {
    $scope.dataFrm = {
        "video_type": "FILE"
    };

    $scope.required = {
        "banner": true,
        "video": true,
        "poster": true
    };

    $scope.fullPageLoader = 1;
    $scope.dataFrmError = {};

    var dataToSend = {};
    dataToSend.call = 'homecontent';
    dataToSend.stype = 'getData';

    var guid = function () {
        var s4 = function () {
            return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
        }
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
            s4() + '-' + s4() + s4() + s4();
    }

    /* Get Home Content Data */
    var promise = $http({
        method: 'POST',
        url: 'cms/homeContent/edit',
        data: dataToSend
    });
    promise.then(
        function (payload) {
            $scope.dataFrm = payload.data.DATA;
            $scope.dataFrm.banner_status = payload.data.DATA.banner_status == 0 ? false : true;
            $scope.dataFrm.video_status = payload.data.DATA.video_status == 0 ? false : true;
            $scope.dataFrm.about_status = payload.data.DATA.about_status == 0 ? false : true;
            $scope.dataFrm.etf_status = payload.data.DATA.etf_status == 0 ? false : true;
            $scope.dataFrm.news_status = payload.data.DATA.news_status == 0 ? false : true;
            $scope.dataFrm.contact_status = payload.data.DATA.contact_status == 0 ? false : true;

            $scope.dataFrm.banner_img = '';
            if (payload.data.DATA.banner_image_disp) {
                $scope.required.banner = false;
                $scope.fuploading_banner_image = 2;
            } else {
                $scope.required.banner = true;
                $scope.fuploading_banner_image = 0;
            }

            $scope.dataFrm.video_file = '';
            if (payload.data.DATA.video_file_disp) {
                $scope.required.video = false;
                $scope.fuploading_video_file = 2;
            } else {
                $scope.required.video = true;
                $scope.fuploading_video_file = 0;
            }

            $scope.dataFrm.video_file_poster = '';
            if (payload.data.DATA.video_file_poster_disp) {
                $scope.required.poster = false;
                $scope.fuploading_video_file_poster = 2;
            } else {
                $scope.required.poster = true;
                $scope.fuploading_video_file_poster = 0;
            }

            $timeout(function () {
                $scope.fullPageLoader = 0;
            }, 1000);
        },
        function (errorPayload) {
            console.log('failure loading', errorPayload);
        }
    );

    /* Ckeditor Options */
    $scope.options = {
        language: 'en',
        allowedContent: true,
        entities: false,
        enterMode: 2,
        extraPlugins: 'divarea',
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

    /* Submit Form */
    $scope.dataFrm.status = 1;
    $scope.submitProcess = 0;
    $scope.submitProcessMsg = '';
    $scope.submit = function () {
        $scope.submitProcess = 1;
        $scope.dataFrm.call = 'homecontent';
        $scope.dataFrm.stype = 'saveData';
        $scope.dataFrmError = {};
        $http({
            method: 'POST',
            url: 'cms/homeContent/save',
            data: $scope.dataFrm
        }).then(function (response) {
            $scope.submitProcess = 2;

            if (response.data.SUCCESS == '1') {
                $scope.submitProcessMsg = '&#x2714; Successfully Saved';
                swal({
                    title: "Success",
                    text: "Successfully Saved",
                    icon: "success",
                    timer: 3000
                });
            } else {
                $scope.submitProcessMsg = '&#x2718; Sorry Cannot Process Your Request';
            }

            $timeout(function () {
                if (response.data.SUCCESS == '1') {
                    window.location.reload();
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
                    scrollTop: $(".home-content-" + Object.keys($scope.dataFrmError)[0]).offset().top - ($("header").height() + 10)
                }, 1000);
            }
        });
    };

    /* Upload Banner */
    $scope.fuploading_banner_image = 0;
    $scope.upload_banner = function (file) {
        if (!file.name) {
            return false;
        }
        $scope.fuploading_banner_image = 1;
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
                $scope.required.banner = false;
                $scope.dataFrm.banner_image = resp.data.IMAGE_NAME;
                $scope.dataFrm.banner_image_disp = resp.data.PATH_TO_IMAGE;
                $scope.fuploading_banner_image = 2;
            } else {
                $scope.fileuploaderror = resp.data.MSG;
                $scope.fuploading_banner_image = 3;
                $scope.required.banner = true;
                $timeout(function () {
                    $scope.fuploading_banner_image = 0;
                }, 3000);

            }
        }, function (resp) {
            console.log('Error status: ' + resp.status);
        }, function (evt) {
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            $scope.fuploading_banner_image = 1;
        });
    };

    /* Remove Banner */
    $scope.removeFile = function (field, type, $event) {
        $event.preventDefault();
        var c = confirm("Are you sure you wish to remove?");
        if (c) {
            $http({
                method: 'POST',
                url: 'cms/home/removeBanner',
                data: {
                    call: 'homecontent',
                    stype: type,
                    field: field
                }
            }).success(function (response) {
                if (response.SUCCESS == 1) {
                    if (type == 'banner_image') {
                        $scope.dataFrm.banner_image_link = '';
                        $scope.fuploading_banner_image = 0;
                        $scope.dataFrm.banner_image = '';
                        $scope.dataFrm.banner_image_disp = '';
                        $scope.required.banner = true;
                    } else if (type == 'video_file') {
                        $scope.dataFrm.video_file_link = '';
                        $scope.fuploading_video_file = 0;
                        $scope.dataFrm.video_file = '';
                        $scope.required.video = true;
                    } else if (type == 'video_file_poster') {
                        $scope.dataFrm.video_file_poster_link = '';
                        $scope.fuploading_video_file_poster = 0;
                        $scope.dataFrm.video_file_poster = '';
                        $scope.required.poster = true;
                    }
                } else {
                    if (type == 'banner_image') {
                        $scope.fuploading_banner_image = 2;
                        $scope.required.banner = false;
                    } else if (type == 'video_file') {
                        $scope.fuploading_video_file = 2;
                        $scope.required.video = false;
                    } else if (type == 'video_file_poster') {
                        $scope.fuploading_video_file_poster = 2;
                        $scope.required.poster = false;
                    }
                }
            });
        }
    };

    /* Upload Video */
    $scope.fuploading_video_file = 0;
    $scope.upload_video = function (file) {
        if (!file.name) {
            return false;
        }
        $scope.fuploading_video_file = 1;
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
                $scope.dataFrm.video_file = resp.data.IMAGE_NAME;
                $scope.dataFrm.video_file_disp = resp.data.PATH_TO_IMAGE;
                $scope.fuploading_video_file = 2;
                $scope.required.video = false;
            } else {
                $scope.fileuploaderror_video_file = resp.data.MSG;
                $scope.fuploading_video_file = 3;
                $scope.required.video = true;
                $timeout(function () {
                    $scope.fuploading_video_file = 0;
                }, 3000);
            }
        }, function (resp) {
            console.log('Error status: ' + resp.status);
        }, function (evt) {
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            $scope.fuploading_video_file = 1;
        });
    };

    /* Upload Video Poster */
    $scope.fuploading_video_file_poster = 0;
    $scope.upload_video_poster = function (file) {
        if (!file.name) {
            return false;
        }
        $scope.fuploading_video_file_poster = 1;
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
                $scope.dataFrm.video_file_poster = resp.data.IMAGE_NAME;
                $scope.dataFrm.video_file_poster_disp = resp.data.PATH_TO_IMAGE;
                $scope.fuploading_video_file_poster = 2;
                $scope.required.poster = false;
            } else {
                $scope.fileuploaderror_video_file_poster = resp.data.MSG;
                $scope.fuploading_video_file_poster = 3;
                $scope.required.poster = true;
                $timeout(function () {
                    $scope.fuploading_video_file_poster = 0;
                }, 3000);
            }
        }, function (resp) {
            console.log('Error status: ' + resp.status);
        }, function (evt) {
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            $scope.fuploading_video_file_poster = 1;
        });
    };
}]);
