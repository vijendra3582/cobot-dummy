angular.module('truemarkApp').controller('resourceController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'ResourceDetail', 'Upload', '$filter', function($http, myConfig, $location, $routeParams, $scope, $timeout, ResourceDetail , Upload, $filter){
    $scope.dataFrm = {};
    $scope.dataFrm.status = 1;
    $scope.submitProcess = 0;
    $scope.dataFrm.id = 0; 
    $scope.dataFrm.set_at_homepage = 0;
     
    $scope.addTitle = 'Add';
    $scope.fullPageLoader = 0;
    $scope.categories = {};
    $scope.toTimestamp = function(date) {
      return new Date(date).getTime();
    };

    var guid = function() {
      var s4 = function() {
        return Math.floor((1 + Math.random()) * 0x10000)
          .toString(16)
          .substring(1);
      }
      return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
        s4() + '-' + s4() + s4() + s4();
    }


    /******/

    var categoryPromise = $http({ method:'GET', url:'cms/resource/categories/list' });
    categoryPromise.then(function(result){
      $scope.categories = result.data.data;
      // console.log($scope.categories);
    });


    /******/

    if($routeParams.id) {
      $scope.fullPageLoader = 1;
      $scope.addTitle = 'Modify';

       var dataToSend = {};
          dataToSend.call = 'resource';
          dataToSend.stype = 'getData'; 
          dataToSend.id = $routeParams.id;
    
      var promise = $http({method: 'POST', url: 'cms/resource/edit', data: dataToSend});
      promise.then(
          function(payload) {
              // console.log(payload);
              if(parseInt(payload.data.length) > parseInt(0))
              { 
                  $scope.dataFrm.status = payload.data.data.status == 0 ? false:true;
                  $scope.dataFrm.set_at_homepage = payload.data.data.set_at_homepage == 0 ? false:true;
                  $scope.dataFrm.id = payload.data.data.id;
                  $scope.dataFrm.title = payload.data.data.title.replace(/\\/g,'');
                  $scope.dataFrm.sub_title = payload.data.data.sub_title ? payload.data.data.sub_title.replace(/\\/g,'') : '';
                  $scope.dataFrm.file_type = payload.data.data.file_type ? payload.data.data.file_type.replace(/\\/g,'') : '';
                  $scope.dataFrm.short_description = payload.data.data.short_description ? payload.data.data.short_description.replace(/\\/g,'') : '';
                   
                  if(payload.data.data.date != "0000-00-00" && payload.data.data.date != null) {
                        $scope.dataFrm.date = $filter('date')(payload.data.data.date, "MM-dd-yyyy");
                  } else {
                        $scope.dataFrm.date = '';
                  }  
                  
                  $scope.dataFrm.resource_type = payload.data.data.resource_type.replace(/\\/g,'');
                  $scope.dataFrm.resource_url = payload.data.data.resource_url ? payload.data.data.resource_url.replace(/\\/g,'') : '';

                  $scope.dataFrm.resource_file = '';
                  if(payload.data.data.resource_file_link)
                  {
                        var fname = payload.data.data.resource_file.replace(/\\/g,'');
                        $scope.resource_file_disp = fname;
                        $scope.fuploading_file = 2;
                        $scope.resource_file_extension = fname.substr(fname.lastIndexOf('.')+1);

                        $scope.resource_file_link = payload.data.data.resource_file_link.replace(/\\/g,'');

                  }
                  else
                  {
                      $scope.dataFrm.resource_file_link = '';
                      $scope.fuploading_file = 0;
                  }


                  $scope.dataFrm.video_file = '';
                    if(payload.data.data.video_file_link)
                    {
                          var fname = payload.data.data.video_file.replace(/\\/g,'');
                          $scope.dataFrm.video_file_disp = payload.data.data.video_file_link;
                          $scope.fuploading_video = 2;
                          $scope.video_file_extension = fname.substr(fname.lastIndexOf('.')+1);
                          $scope.video_file_link = payload.data.data.video_file_link.replace(/\\/g,'');

                    }
                    else
                    {
                        $scope.dataFrm.video_file_link = '';
                        $scope.fuploading_video = 0;
                    }

                    $scope.dataFrm.video_image = '';
                    if(payload.data.data.video_image_link)
                    {
                        $scope.dataFrm.video_image_link = payload.data.data.video_image_link.replace(/\\/g,'');
                        $scope.fuploading = 2;
                    }
                    else
                    {
                        $scope.dataFrm.video_image_link = '';
                        $scope.fuploading = 0;
                    }

                  $timeout(function(){
                      $scope.fullPageLoader = 0;
                  }, 1000);
            }
            else
            {
                //location.href = "index.php";
                $location.path('/welcome');
            }


          },
          function(errorPayload) {
              console.log('failure loading', errorPayload);
          }


      );

    } else {

        $scope.dataFrm = {};
        $scope.dataFrm.resource_image = '';
        $scope.dataFrm.resource_image_disp = '';

        $scope.resource_file_disp = '';
        $scope.fuploading_file = 0;
        $scope.resource_file_extension = '';
        $scope.resource_file_link = '#';

        $scope.dataFrm.resource_type = "URL";

        $scope.dataFrm.status = true;
        $scope.dataFrm.set_at_homepage = 0;
        $scope.dataFrm.video_image = '';
        $scope.dataFrm.video_image_link = '';


    }

    $scope.submitProcess = 0;
    $scope.submitProcessMsg = '';




    $scope.fuploading_file = 0;
    // upload on file select or drop
    $scope.uploadFile = function (file) {
      if(!file.name){
         return false;
      }
        $scope.fuploading_file = 1;
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
                //$scope.dataFrm.news_file = resp.data.IMAGE_NAME;
                //$scope.dataFrm.news_file_disp = resp.data.PATH_TO_IMAGE;

                $scope.dataFrm.resource_file = resp.data.IMAGE_NAME;
                $scope.resource_file_disp = resp.data.IMAGE_NAME;
                $scope.resource_file_extension = resp.data.IMAGE_EXTENSION;


                $scope.fuploading_file = 2;
            }
            else
            {
                $scope.fileuploaderror_file = resp.data.MSG;
                $scope.fuploading_file = 3;

                $timeout(function(){
                    $scope.fuploading_file = 0;
                }, 3000);

            }

            //console.log(resp.data.PATH_TO_IMAGE);

        }, function (resp) {
            console.log('Error status: ' + resp.status);
        }, function (evt) {
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            //console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
            $scope.fuploading_file = 1;
        });
    };




    $scope.submit = function() {
        $scope.submitProcess = 1;
        $scope.dataFrm.call = 'resource';
        $scope.dataFrm.stype = 'saveResource';

        $http({method: 'POST', url: 'cms/resource/save', data: $scope.dataFrm}).success(function(response){
              $scope.submitProcess = 2;

              if(response.SUCCESS == '1')
              {
                  $scope.submitProcessMsg = '&#x2714; Successfully Saved';
                  swal({
                    title: "Success",
                    text:  ' Successfully Saved. ',
                    icon: "success",
                    timer: 3000
                    //button: "Ok",
                  });

              }
              else if(response.SUCCESS == '2')
              {
                  $scope.submitProcessMsg = '&#x2757; Already Exists';
              }
              else
              {
                  $scope.submitProcessMsg = '&#x2718; Sorry Cannot Process Your Request';
              }

              console.log(response);
              $timeout(function(){
                  $scope.submitProcess = 0;
                  if(response.SUCCESS == '1') {
                      $location.path("/listResources/1");
                      $scope.dataFrm = {};
                  }
              }, 3000);

          });


    };

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


    ////////// video ================

    $scope.fuploading_video = 0;
    // upload on file select or drop
    $scope.uploadVideo = function (file) {
      if(!file.name){
         return false;
      } 
      $scope.fuploading_video = 1;
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
                $scope.dataFrm.video_file = resp.data.IMAGE_NAME;
                $scope.dataFrm.video_file_disp = resp.data.PATH_TO_IMAGE;
                $scope.fuploading_video = 2;
            }
            else
            {
                $scope.fileuploaderror_video = resp.data.MSG;
                $scope.fuploading_video = 3;

                $timeout(function(){
                    $scope.fuploading_video = 0;
                }, 3000);

            }

            $scope.showVideoProgress = '';

            //console.log(resp.data.PATH_TO_IMAGE);

        }, function (resp) {
            console.log('Error status: ' + resp.status);
        }, function (evt) {
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            //console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
            $scope.showVideoProgress = progressPercentage + '% ';
            $scope.fuploading_video = 1;
        });
    };

   $scope.fuploading = 0;
   // upload on file select or drop
   $scope.upload = function (file) {
       $scope.fuploading = 1;
      if(!file.name){
         return false;
      }
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
               $scope.dataFrm.video_image = resp.data.IMAGE_NAME;
               $scope.dataFrm.video_image_link = resp.data.PATH_TO_IMAGE;
               $scope.fuploading = 2;
           }
           else
           {
               $scope.fileuploaderror = resp.data.MSG;
               $scope.fuploading = 3;

               $timeout(function(){
                   $scope.fuploading = 0;
               }, 3000);

           }

           //console.log(resp.data.PATH_TO_IMAGE);

       }, function (resp) {
           console.log('Error status: ' + resp.status);
       }, function (evt) {
           var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
           //console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
           $scope.fuploading = 1;
       });
   };

   $scope.removeFile = function(field, type, $event) {
        $event.preventDefault();
        var c = confirm("Are you sure you wish to remove?");
        if(c) 
        {
            if($routeParams.id) {
                $http({method: 'POST', url: 'cms/resource/remove-image', data: {call: 'resource', stype:'removeImage',field:field, id: $routeParams.id, type:type}}).success(function(response){
                    //console.log(response);
                    if(type == 'FILE'){
                      $scope.fuploading_file = 0;
                      $scope.dataFrm.resource_file = '';
                      $scope.dataFrm.resource_file_disp = '';
                    }
                    if(type == 'VIDEO'){
                      $scope.fuploading_video = 0;
                      $scope.dataFrm.video_file = ''; 
                      $scope.dataFrm.video_file_disp = ''; 
                    }
                    if(type == 'VIDEO_POSTER'){
                      $scope.fuploading = 0;
                      $scope.dataFrm.video_image = ''; 
                      $scope.dataFrm.video_image_link = ''; 
                    }

                }); 
            } else {
                if(type == 'FILE'){
                  $scope.fuploading_file = 0;
                  $scope.dataFrm.resource_file = '';
                  $scope.dataFrm.resource_file_disp = '';
                }
                if(type == 'VIDEO'){
                  $scope.fuploading_video = 0;
                  $scope.dataFrm.video_file = ''; 
                  $scope.dataFrm.video_file_disp = ''; 
                }
                if(type == 'VIDEO_POSTER'){
                  $scope.fuploading = 0;
                  $scope.dataFrm.video_image = ''; 
                  $scope.dataFrm.video_image_link = ''; 
                }
            }
                
        }
         
        
    };




}]);
