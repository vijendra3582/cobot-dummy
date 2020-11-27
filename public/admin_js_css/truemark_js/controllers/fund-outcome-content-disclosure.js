angular.module('truemarkApp').controller('fundOutcomeContentDisclosureController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'Upload', function($http, myConfig, $location, $routeParams, $scope, $timeout, Upload){
      $scope.dataFrm = {};
      $scope.fullPageLoader = 1;
      
      var dataToSend = {};
      dataToSend.call = 'fundOutcomeContentDisclosure';
      dataToSend.stype = 'getData';

      var guid = function() {
          var s4 = function() {
            return Math.floor((1 + Math.random()) * 0x10000)
              .toString(16)
              .substring(1);
          }
          return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
            s4() + '-' + s4() + s4() + s4();
        }

      var promise = $http({method: 'POST', url: 'cms/fundOutcomeContentDisclosure/edit', data: dataToSend});

      promise.then(
          function(payload) { 
              $scope.dataFrm = payload.data.DATA; 
              
                  /*$scope.dataFrm.meta_title = payload.data.DATA.meta_title ? payload.data.DATA.meta_title : '';
                  $scope.dataFrm.meta_description = payload.data.DATA.meta_description ? payload.data.DATA.meta_description : '';
                  $scope.dataFrm.meta_keyword = payload.data.DATA.meta_keyword ? payload.data.DATA.meta_keyword : '';
                  $scope.dataFrm.short_description = payload.data.DATA.short_description ? payload.data.DATA.short_description : '';*/

                  $scope.dataFrm.title = payload.data.DATA.title ? payload.data.DATA.title : '';
                  $scope.dataFrm.sub_title = payload.data.DATA.sub_title ? payload.data.DATA.sub_title : '';
                  $scope.dataFrm.description = payload.data.DATA.description ? payload.data.DATA.description :'';
                  $scope.dataFrm.description = payload.data.DATA.description ? payload.data.DATA.description :'';
                  $scope.dataFrm.disclosure = payload.data.DATA.disclosure ? payload.data.DATA.disclosure :'';
                  $scope.dataFrm.banner_footer_txt = payload.data.DATA.banner_footer_txt ? payload.data.DATA.banner_footer_txt :'';

                  
                  $scope.dataFrm.structured_outcome_title = payload.data.DATA.structured_outcome_title ? payload.data.DATA.structured_outcome_title : '';
                  $scope.dataFrm.structured_outcome_subtitle = payload.data.DATA.structured_outcome_subtitle ? payload.data.DATA.structured_outcome_subtitle : '';
                  $scope.dataFrm.structured_outcome_short_desc = payload.data.DATA.structured_outcome_short_desc ? payload.data.DATA.structured_outcome_short_desc : '';

                  $scope.dataFrm.banner_img = ''; 
                  if(payload.data.DATA.banner_img_disp)
                  {
                      $scope.dataFrm.banner_img_disp = payload.data.DATA.banner_img_disp;
                      $scope.fuploading_banner_img = 2;
                  }
                  else
                  {
                      $scope.dataFrm.banner_img_disp = '';
                      $scope.fuploading_banner_img = 0;
                  }
              $timeout(function(){
                  $scope.fullPageLoader = 0;
              }, 1000);
          },
          function(errorPayload) {
              console.log('failure loading', errorPayload);
          }
      );

      $scope.submitProcess = 0;
      $scope.submitProcessMsg = '';

      // Editor options.
      $scope.options = {
        language: 'en',
        allowedContent: true,
        entities: false,
        enterMode: 2,
        extraPlugins: 'divarea,contactusPopUp',
        filebrowserBrowseUrl : 'admin_js_css/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl : 'admin_js_css/ckfinder/ckfinder.html?type=Images',
        filebrowserFlashBrowseUrl : 'admin_js_css/ckfinder/ckfinder.html?type=Flash',
        filebrowserUploadUrl : 'admin_js_css/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : 'admin_js_css/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl : 'admin_js_css/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    };

    // Called when the editor is completely ready. for ckeditor
    $scope.onReady = function () {
        // ...
        //console.log("ckeditor is ready......");
    };

    // $scope.dataFrm = {};
    $scope.dataFrm.status = 1;
    $scope.submitProcess = 0;

    $scope.submit = function() {
        $scope.submitProcess = 1;
        $scope.dataFrm.call = 'fundOutcomeContentDisclosure';
        $scope.dataFrm.stype = 'saveData';

        $http({method: 'POST', url: 'cms/fundOutcomeContentDisclosure/save', data: $scope.dataFrm}).success(function(response){
              $scope.submitProcess = 2;

              if(response.SUCCESS == '1')
              {
                  $scope.submitProcessMsg = '&#x2714; Successfully Saved';
                  swal({
                    title: "Success",
                    text: "Successfully Saved",
                    icon: "success",
                    timer: 3000
                    //button: "Ok",
                  });
              }
              else
              {
                  $scope.submitProcessMsg = '&#x2718; Sorry Cannot Process Your Request';
                  // swal({
                  //   title: "Sorry",
                  //   text: "Cannot Process Your Request",
                  //   icon: "error",
                  //   timer: 3000
                  //   //button: "Ok",
                  // });
              }

              console.log(response);
              $timeout(function(){
                  //$scope.submitProcess = 0;
                  if(response.SUCCESS == '1') {
                      window.location.reload();
                  }
              }, 3000);

          });
    };

    /** upload banner_img **/

  $scope.fuploading_banner_img = 0;
    $scope.upload_banner = function (file) {
        $scope.fuploading_banner_img = 1;
        var uuid = guid();
        Upload.upload({
            //url: myConfig.upload_url,
            //data: {file: file}

            url: 'cms/chunk_upload',
            resumeChunkSize: '2MB',
            data: {
                filename: file.name,
                file: file,
                uuid: uuid
            }


        }).then(function (resp) {
            //console.log('Success ' + resp.config.data.file.name + 'uploaded. Response: ' + resp.data);
            if(resp.data.SUCCESS == "1")
            {
                $scope.dataFrm.banner_img = resp.data.IMAGE_NAME;
                $scope.dataFrm.banner_img_disp = resp.data.PATH_TO_IMAGE;
                $scope.fuploading_banner_img = 2;
            }
            else
            {
                $scope.fileuploaderror = resp.data.MSG;
                $scope.fuploading_banner_img = 3;

                $timeout(function(){
                    $scope.fuploading_banner_img = 0;
                }, 3000);

            }

            //console.log(resp.data.PATH_TO_IMAGE);

        }, function (resp) {
            console.log('Error status: ' + resp.status);
        }, function (evt) {
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            //console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
            $scope.fuploading_banner_img = 1;
        });
    };


   $scope.removeFile = function(field, type, $event) {
        $event.preventDefault();
         // console.log($scope.dataFrm.id);
        var c = confirm("Are you sure you wish to remove?");

        if(c) {
            $http({method: 'POST', url: 'cms/fundOutcomeContentDisclosure/removeBanner', data: {call: 'FundOutcome', stype:type, field:field}}).success(function(response){
                //console.log(response);  
              if (response.SUCCESS== 1) {
                  
                    $scope.dataFrm.banner_img_disp = '';
                    $scope.fuploading_banner_img=0;
                    $scope.dataFrm.banner_img = ''; 
                                
              } else {
                    $scope.fuploading_banner_img = 2;
              }


          });
        }                 
    };

}]);
