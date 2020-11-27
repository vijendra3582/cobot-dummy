angular.module('truemarkApp').controller('newsDisclosureController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'Upload', function ($http, myConfig, $location, $routeParams, $scope, $timeout, Upload) {
    $scope.dataFrm = {};
    $scope.fullPageLoader = 1;
    $scope.required = {
        "banner": true
    };
    $scope.dataFrmError = {};

    var dataToSend = {};
    dataToSend.call = 'news';
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

    /* Get Content & Disclosure Data */
    var promise = $http({
        method: 'POST',
        url: 'cms/newsDisclosure/edit',
        data: dataToSend
    });

    promise.then(
        function (payload) {
            $scope.dataFrm = payload.data.DATA;

            $scope.dataFrm.banner_img = '';
            if (payload.data.DATA.banner_img_disp) {
                $scope.fuploading_banner_img = 2;
                $scope.required.banner = false;
            } else {
                $scope.fuploading_banner_img = 0;
                $scope.required.banner = true;
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
        $scope.dataFrm.call = 'news';
        $scope.dataFrm.stype = 'saveData';
        $scope.dataFrmError = {};
        $http({
            method: 'POST',
            url: 'cms/newsDisclosure/save',
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
                    scrollTop: $(".news-" + Object.keys($scope.dataFrmError)[0]).offset().top - ($("header").height() + 10)
                }, 1000);
            }
        });
    };

    /* Upload Banner */
    $scope.fuploading_banner_img = 0;
    $scope.upload_banner = function (file) {
        $scope.fuploading_banner_img = 1;
        var uuid = guid();
        Upload.upload({url: 'cms/chunk_upload',
            resumeChunkSize: '2MB',
            data: {
                filename: file.name,
                file: file,
                uuid: uuid
            }
        }).then(function (resp) {
            if (resp.data.SUCCESS == "1") {
                $scope.dataFrm.banner_img = resp.data.IMAGE_NAME;
                $scope.dataFrm.banner_img_disp = resp.data.PATH_TO_IMAGE;
                $scope.fuploading_banner_img = 2;
                $scope.required.banner = false;
            } else {
                $scope.fileuploaderror = resp.data.MSG;
                $scope.fuploading_banner_img = 3;
                $scope.required.banner = true;
                $timeout(function () {
                    $scope.fuploading_banner_img = 0;
                }, 3000);
            }
        }, function (resp) {
            console.log('Error status: ' + resp.status);
        }, function (evt) {
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            $scope.fuploading_banner_img = 1;
        });
    };

    /* Remove Banner */
    $scope.removeFile = function (field, type, $event) {
        $event.preventDefault();
        var c = confirm("Are you sure you wish to remove?");
        if (c) {
            $http({
                method: 'POST',
                url: 'cms/newsDisclosure/removeBanner',
                data: {
                    call: 'news',
                    stype: type,
                    field: field
                }
            }).success(function (response) {
                if (response.SUCCESS == 1) {
                    $scope.dataFrm.banner_img_link = '';
                    $scope.fuploading_banner_img = 0;
                    $scope.dataFrm.banner_img = '';
                    $scope.required.banner = true;
                } else {
                    $scope.fuploading_banner_img = 2;
                    $scope.required.banner = false;
                }
            });
        }
    };
}]);
