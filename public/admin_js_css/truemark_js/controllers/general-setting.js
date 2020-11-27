angular.module('truemarkApp').controller('generalSettingController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'Upload', function($http, myConfig, $location, $routeParams, $scope, $timeout, Upload){
      $scope.dataFrm = {};
      $scope.fullPageLoader = 1;

      var dataToSend = {};
      dataToSend.call = 'generalsetting';
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

      var promise = $http({method: 'POST', url: 'cms/generalSetting/edit', data: dataToSend});

      promise.then(
          function(payload) { 
              $scope.dataFrm = payload.data.DATA; 
              console.log($scope.dataFrm);
                  $scope.dataFrm.telephone = payload.data.DATA.telephone ? payload.data.DATA.telephone : '';
                  $scope.dataFrm.company_name = payload.data.DATA.company_name ? payload.data.DATA.company_name : '';
                  $scope.dataFrm.info_email = payload.data.DATA.info_email ? payload.data.DATA.info_email : '';
                  //$scope.dataFrm.slug = payload.data.DATA.slug ? payload.data.DATA.slug : '';

                  $scope.dataFrm.address = payload.data.DATA.address ? payload.data.DATA.address : '';
                  
                  $scope.dataFrm.facebook_url = payload.data.DATA.facebook_url ? payload.data.DATA.facebook_url :'';
                  // $scope.dataFrm.instagram_url = payload.data.DATA.instagram_url ? payload.data.DATA.instagram_url :'';
                  $scope.dataFrm.twitter_url = payload.data.DATA.twitter_url ? payload.data.DATA.twitter_url :'';
                  $scope.dataFrm.linkedin_url = payload.data.DATA.linkedin_url ? payload.data.DATA.linkedin_url :'';
                  

                  $scope.dataFrm.copyrights = payload.data.DATA.copyrights ? payload.data.DATA.copyrights :'';
                  $scope.dataFrm.location_url = payload.data.DATA.location_url ? payload.data.DATA.location_url :'';
                  $scope.dataFrm.contact_us_header = payload.data.DATA.contact_us_header ? payload.data.DATA.contact_us_header :'';
                  $scope.dataFrm.contact_us_footer = payload.data.DATA.contact_us_footer ? payload.data.DATA.contact_us_footer :'';
                  $scope.dataFrm.subscribe_header = payload.data.DATA.subscribe_header ? payload.data.DATA.subscribe_header :'';
                  $scope.dataFrm.subscribe_footer = payload.data.DATA.subscribe_footer ? payload.data.DATA.subscribe_footer :'';
                  $scope.dataFrm.contact_us_mail_to = payload.data.DATA.contact_us_mail_to ? payload.data.DATA.contact_us_mail_to :'';


                  $scope.dataFrm.enable_map_button = payload.data.DATA.enable_map_button == parseInt(1) ? true : false;
                  $scope.dataFrm.button_txt = payload.data.DATA.button_txt ? payload.data.DATA.button_txt :'';

                  $scope.dataFrm.map_background_img = '';
                  if(payload.data.DATA.map_background_img_disp)
                  {
                      $scope.dataFrm.map_background_img_disp = payload.data.DATA.map_background_img_disp ? payload.data.DATA.map_background_img_disp.replace(/\\/g,'') : '';
                      $scope.fuploading_map_background = 2;
                  }
                  else
                  {
                      $scope.dataFrm.map_background_img_disp = '';
                      $scope.fuploading_map_background = 0;
                  }

                  $scope.dataFrm.map_img = '';
                  if(payload.data.DATA.map_img_disp)
                  {
                      $scope.dataFrm.map_img_disp = payload.data.DATA.map_img_disp ? payload.data.DATA.map_img_disp.replace(/\\/g,'') : '';
                      $scope.fuploading_map = 2;
                  }
                  else
                  {
                      $scope.dataFrm.map_img_disp = '';
                      $scope.fuploading_map = 0;
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
        extraPlugins: 'divarea',
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
    $scope.dataFrm.enable_map_button = 1;
    $scope.submitProcess = 0;

    $scope.submit = function() {
        $scope.submitProcess = 1;
        $scope.dataFrm.call = 'generalsettings';
        $scope.dataFrm.stype = 'saveData';

        $http({method: 'POST', url: 'cms/generalSetting/save', data: $scope.dataFrm}).success(function(response){
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
              else if(response.SUCCESS == '2'){

                $scope.submitProcessMsg = '&#x2714;'+response.MSG;
                   swal({
                    title: "Error",
                    text: response.MSG,
                    icon: "error",
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

              //console.log(response);
              $timeout(function(){
                  //$scope.submitProcess = 0;
                  if(response.SUCCESS == '1') {
                      window.location.reload();
                  }
              }, 3000);

          });
    };

    /** upload banner_img **/

    $scope.fuploading_map_background = 0;
    $scope.fuploading_map = 0;
    $scope.upload_banner = function (file, $type) {
        if(!file.name){
          return false;
        }  

        if($type == 'background'){
          $scope.fuploading_map_background = 1;
        }
        else{
          $scope.fuploading_map = 1;
        }

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
            //console.log('Success ' + resp.config.data.file.name + 'uploaded. Response: ' + resp.data);
            if(resp.data.SUCCESS == "1")
            {
                if($type == 'background'){
                  $scope.dataFrm.map_background_img = resp.data.IMAGE_NAME;
                  $scope.dataFrm.map_background_img_disp = resp.data.PATH_TO_IMAGE;
                  $scope.fuploading_map_background = 2;
                }else{
                  $scope.dataFrm.map_img = resp.data.IMAGE_NAME;
                  $scope.dataFrm.map_img_disp = resp.data.PATH_TO_IMAGE;
                  $scope.fuploading_map = 2;
                }
            }
            else
            {
                if($type == 'background'){
                  $scope.fileuploaderror1 = resp.data.MSG;
                  $scope.fuploading_map_background = 3;

                  $timeout(function(){
                      $scope.fuploading_map_background = 0;
                  }, 3000);
                }else{
                  $scope.fileuploaderror = resp.data.MSG;
                  $scope.fuploading_map = 3;

                  $timeout(function(){
                      $scope.fuploading_map = 0;
                  }, 3000);
                }

                

            }

            //console.log(resp.data.PATH_TO_IMAGE);

        }, function (resp) {
            console.log('Error status: ' + resp.status);
        }, function (evt) {
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            //console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
              
            if($type == 'background'){
              $scope.fuploading_map_background = 1;
            }
            else{
              $scope.fuploading_map = 1;
            }
              

        });
    };


    $scope.removeImage = function(field, type, $event) {
        $event.preventDefault();
         // console.log($scope.dataFrm.id);
        var c = confirm("Are you sure you wish to remove?");

        if(c) {
            $http({method: 'POST', url: 'cms/generalSetting/removeBanner', data: {call: 'homecontent', stype:type, field:field}}).success(function(response){
                //console.log(response);  
              if (response.SUCCESS== 1) {
                  if(type == 'background'){
                    $scope.dataFrm.map_background_img_disp = '';
                    $scope.fuploading_map_background=0;
                    $scope.dataFrm.map_background_img = ''; 
                  }else{
                    $scope.dataFrm.map_img_disp = '';
                    $scope.fuploading_map=0;
                    $scope.dataFrm.map_img = ''; 
                  }                  
              } else {
                  if(type == 'background'){
                    $scope.fuploading_map_background=2;
                  }else{
                    $scope.fuploading_map=2;
                  }     
              }


          });
        }                 
    };

}]);
