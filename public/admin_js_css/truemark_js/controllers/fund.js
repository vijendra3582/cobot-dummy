angular.module('truemarkApp').controller('fundController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'FundDetail', 'Upload', '$filter', function($http, myConfig, $location, $routeParams, $scope, $timeout, FundDetail , Upload, $filter){

    $scope.dataFrm = {};
    $scope.dataFrm.fund_id = 0;
    $scope.addTitle = 'Add';
    $scope.fullPageLoader = 0;
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

    if($routeParams.id) {
        $scope.fullPageLoader = 1;
        $scope.addTitle = 'Modify';

        var dataToSend = {};
          dataToSend.call = 'fund';
          dataToSend.stype = 'getData'; 
          dataToSend.id = $routeParams.id;
    
        var promise = $http({method: 'POST', url: 'cms/fund/edit', data: dataToSend});;

        promise.then(
            function(payload) {
                console.log(payload);

                if(parseInt(payload.data.length) > parseInt(0))
                {
                    //menu title ======================

                    $scope.dataFrm.status = payload.data.data.status == 0 ? false:true;
                    $scope.dataFrm.is_premium_discount = payload.data.data.is_premium_discount == 0 ? false:true;
                    $scope.dataFrm.is_outcome_product = payload.data.data.is_outcome_product == 0 ? false:true;
                    $scope.dataFrm.product_series = payload.data.data.product_series ? payload.data.data.product_series : '';

                    $scope.dataFrm.fund_id = payload.data.data.id;
                    $scope.dataFrm.fund_profile_id = payload.data.data.fund_profile_id;
                    $scope.dataFrm.fund_name = payload.data.data.fund_name.replace(/\\/g,'');
                    $scope.dataFrm.sub_title = payload.data.data.sub_title.replace(/\\/g,'');
                    $scope.dataFrm.menu_title = payload.data.data.menu_title ? payload.data.data.menu_title.replace(/\\/g,'') : '';

                    $scope.dataFrm.fund_ticker = payload.data.data.fund_ticker.replace(/\\/g,'');
                    $scope.dataFrm.index_learn_more_link = payload.data.data.index_learn_more_link ? payload.data.data.index_learn_more_link.replace(/\\/g,'') :'';
                    if(payload.data.data.fund_inception_date != "0000-00-00") {
                          $scope.dataFrm.fund_inception_date = $filter('date')(payload.data.data.fund_inception_date, "MM-dd-yyyy"); // for conversion to string
                    } else {
                          $scope.dataFrm.fund_inception_date = '';
                    }
                    
                    if(payload.data.data.launch_date != "0000-00-00" && payload.data.data.launch_date != null) {
                          $scope.dataFrm.launch_date = $filter('date')(payload.data.data.launch_date, "MM-dd-yyyy"); // for conversion to string
                    } else {
                          $scope.dataFrm.launch_date = '';
                    }
                    
                    if(payload.data.data.end_date != "0000-00-00" && payload.data.data.end_date != null) {
                          $scope.dataFrm.end_date = $filter('date')(payload.data.data.end_date, "MM-dd-yyyy"); // for conversion to string
                    } else {
                          $scope.dataFrm.end_date = '';
                    }

                    if(payload.data.data.fund_data_pricing_notes) {
                        $scope.dataFrm.fund_data_pricing_notes = payload.data.data.fund_data_pricing_notes ? payload.data.data.fund_data_pricing_notes.replace(/\\/g,'') : '';
                    }

                    if(payload.data.data.holdings_notes) {
                        $scope.dataFrm.holdings_notes = payload.data.data.holdings_notes ? payload.data.data.holdings_notes.replace(/\\/g,'') : '';
                    }

                    if(payload.data.data.fund_index_description) {
                        $scope.dataFrm.fund_index_description = payload.data.data.fund_index_description ? payload.data.data.fund_index_description.replace(/\\/g,'') : '';
                    }

                    if(payload.data.data.fund_description) {
                        $scope.dataFrm.fund_description = payload.data.data.fund_description ? payload.data.data.fund_description.replace(/\\/g,'') : '';
                    }

                    if(payload.data.data.fund_short_description) {
                        $scope.dataFrm.fund_short_description = payload.data.data.fund_short_description ? payload.data.data.fund_short_description.replace(/\\/g,'') : '';
                    }

                    $scope.dataFrm.fund_disclosure = payload.data.data.fund_disclosure ? payload.data.data.fund_disclosure.replace(/\\/g,'') : '';
                    $scope.dataFrm.performance_description = payload.data.data.performance_description ? payload.data.data.performance_description.replace(/\\/g,'') : '';

                    $scope.dataFrm.fund_detail_notes = payload.data.data.fund_detail_notes ? payload.data.data.fund_detail_notes.replace(/\\/g,'') : '';



                    $scope.dataFrm.banner_image = '';
                      if(payload.data.data.banner_image_disp)
                      {
                          $scope.dataFrm.banner_image_disp = payload.data.data.banner_image_disp ? payload.data.data.banner_image_disp.replace(/\\/g,'') : '';
                          $scope.fuploading_banner_image = 2;
                      }
                      else
                      {
                          $scope.dataFrm.banner_image_disp = '';
                          $scope.fuploading_banner_image = 0;
                      }

                     $scope.dataFrm.fund_image = '';
                      if(payload.data.data.fund_image_disp)
                      {
                          $scope.dataFrm.fund_image_disp = payload.data.data.fund_image_disp ? payload.data.data.fund_image_disp.replace(/\\/g,'') : '';
                          $scope.fuploading = 2;
                      }
                      else
                      {
                          $scope.dataFrm.fund_image_disp = '';
                          $scope.fuploading = 0;
                      }

                      $scope.dataFrm.fund_banner_logo = '';
                      if(payload.data.data.fund_banner_logo_disp)
                      {
                          $scope.dataFrm.fund_banner_logo_disp = payload.data.data.fund_banner_logo_disp ? payload.data.data.fund_banner_logo_disp.replace(/\\/g,'') : '';
                          $scope.fuploading_banner_logo = 2;
                      }
                      else
                      {
                          $scope.dataFrm.fund_banner_logo_disp = '';
                          $scope.fuploading_banner_logo = 0;
                      }

                    $scope.dataFrm.premium_discount_file = '';
                    if(payload.data.data.premium_discount_file)
                      {
                          $scope.premium_discount_file_disp = payload.data.data.premium_discount_file ? payload.data.data.premium_discount_file : '';
                          $scope.dataFrm.premium_discount_file_link = payload.data.data.premium_discount_file_link ? payload.data.data.premium_discount_file_link.replace(/\\/g,'') : '';
                          $scope.fuploading_premium_discount_file = 2;
                      }
                      else
                      {
                          $scope.dataFrm.premium_discount_file_link = '';
                          $scope.fuploading_premium_discount_file = 0;
                      }


                    $scope.dataFrm.url_key = payload.data.data.url_key;
                    $scope.dataFrm.meta_title = payload.data.data.meta_title;
                    $scope.dataFrm.meta_keyword = payload.data.data.meta_keyword;
                    $scope.dataFrm.meta_description = payload.data.data.meta_description;

                    if(payload.data.data.banner_transparency != '') {
                      $scope.dataFrm.banner_transparency = payload.data.data.banner_transparency;
                    }

                    $timeout(function(){
                        $scope.fullPageLoader = 0;
                    }, 1000);
                }
                else
                {
                   $location.path('/welcome');
                }

            },
            function(errorPayload) {
                console.log('failure loading fund', errorPayload);
            }


        );

    } else {

        $scope.dataFrm = {};
        $scope.dataFrm.status = true;
        $scope.dataFrm.is_outcome_product = false;
        $scope.dataFrm.is_premium_discount = true;
        $scope.dataFrm.fund_id = 0;
        
    } 

    $scope.submitProcess = 0;
    $scope.submitProcessMsg = '';

    $scope.submit = function() {
        $scope.submitProcess = 1;
        $scope.dataFrm.call = 'fund';
        $scope.dataFrm.stype = 'saveFund';

        $http({method: 'POST', url: 'cms/fund/save', data: $scope.dataFrm}).success(function(response){
              $scope.submitProcess = 2;

              if(response.SUCCESS == '1') {
                  $scope.submitProcessMsg = '&#x2714; Successfully Saved';
                  swal({
                    title: "Success",
                    text: "Successfully Saved",
                    icon: "success",
                    timer: 3000
                  })
              } else if(response.SUCCESS == '2') {
                  $scope.submitProcessMsg = '&#x2757; Already Exists';
                  swal({
                    title: "Error",
                    text: response.MSG,
                    icon: "error",
                    timer: 3000
                  })
              } else {
                  $scope.submitProcessMsg = '&#x2718; Sorry Cannot Process Your Request';
              }

              console.log(response);
              $timeout(function(){
                  $scope.submitProcess = 0;
                  if(response.SUCCESS == '1') {
                      $location.path("/listFunds/1");
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



    $scope.fuploading_banner_image = 0;
    $scope.upload_banner = function (file) {
        if(!file.name){
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
            //console.log('Success ' + resp.config.data.file.name + 'uploaded. Response: ' + resp.data);
            if(resp.data.SUCCESS == "1")
            {
                $scope.dataFrm.banner_image = resp.data.IMAGE_NAME;
                $scope.dataFrm.banner_image_disp = resp.data.PATH_TO_IMAGE;
                $scope.fuploading_banner_image = 2;
            }
            else
            {
                $scope.fileuploaderror = resp.data.MSG;
                $scope.fuploading_banner_image = 3;

                $timeout(function(){
                    $scope.fuploading_banner_image = 0;
                }, 3000);

            }

            //console.log(resp.data.PATH_TO_IMAGE);

        }, function (resp) {
            console.log('Error status: ' + resp.status);
        }, function (evt) {
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            //console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
            $scope.fuploading_banner_image = 1;
        });
    };

    $scope.removeImage = function(file,field,$event) {
        var c = confirm("Are you sure you wish to remove?");
        $event.preventDefault();
        if (field)
          if(c) {
              if($routeParams.id) {

                  $http({method: 'POST', url:'cms/fund/removeFile', data: {call: 'fund', stype:'removeImage', field:field, file:file, fund_id: $routeParams.id}}).success(function(response){
                      if (response.SUCCESS==1) {
                        $scope['fuploading_'+field] = 0;
                        $scope.dataFrm[field] = '';
                        $scope.dataFrm[field + '_disp'] = '';
                      } else {
                        $scope['fuploading_'+field] = 2;
                      }
                  });
              } else {
                    $scope['fuploading_'+field] = 0;
                    $scope.dataFrm[field] = '';
                    $scope.dataFrm[field + '_disp'] = '';
              }


          }
        //else
          //alert("Pleast refresh the page");
    };



    // upload on file select or drop
    $scope.fuploading_premium_discount_file = 0;
    $scope.upload_premium_discount = function (file) {
      if(!file.name){
        return false;
      }
        $scope.fuploading_premium_discount_file = 1;
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
                $scope.dataFrm.premium_discount_file = resp.data.IMAGE_NAME;
                $scope.premium_discount_file_disp = resp.data.IMAGE_NAME;
                $scope.premium_discount_file_extension = resp.data.IMAGE_EXTENSION;
                $scope.fuploading_premium_discount_file = 2;
            }
            else
            {
                $scope.fileuploaderror_premium_discount_file = resp.data.MSG;
                $scope.fuploading_premium_discount_file = 3;

                $timeout(function(){
                    $scope.fuploading_premium_discount_file = 0;
                }, 3000);

            }

            //console.log(resp.data.PATH_TO_IMAGE);

        }, function (resp) {
            console.log('Error status: ' + resp.status);
        }, function (evt) {
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            //console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
            $scope.fuploading_premium_discount_file = 1;
        });
    };


    $scope.removePremiumDiscountFile = function() {
        var c = confirm("Are you sure you wish to remove?");
        if(c)
        {
            if($routeParams.id) {
                $http({method: 'POST', url: 'cms/fund/removeFile', data: {call: 'fund', stype:'removePremiumDiscountFile',field:'premium_discount', fund_id: $routeParams.id}}).success(function(response){
                    //console.log(response);
                    $scope.fuploading_premium_discount_file = 0;
                    $scope.dataFrm.premium_discount_file = '';
                    $scope.dataFrm.premium_discount_file_link = '';
                });
            } else {
                $scope.fuploading_premium_discount_file = 0;
                $scope.dataFrm.premium_discount_file = '';
                $scope.dataFrm.premium_discount_file_link = '';
            }
        }
    };


    /** fund logo image **/
    $scope.fuploading = 0;
    // upload on file select or drop
    $scope.upload = function (file) {
      if(!file.name){
        return false;
      }
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
            //console.log('Success ' + resp.config.data.file.name + 'uploaded. Response: ' + resp.data);
            if(resp.data.SUCCESS == "1")
            {
                $scope.dataFrm.fund_image = resp.data.IMAGE_NAME;
                $scope.dataFrm.fund_image_disp = resp.data.PATH_TO_IMAGE;
                $scope.fuploading = 2;
            }
            else
            {
                $scope.fileuploaderror_fund = resp.data.MSG;
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

    /** fund banner logo image **/
    $scope.fuploading_banner_logo = 0;
    // upload on file select or drop
    $scope.uploadBannerLogo = function (file) {
      if(!file.name){
        return false;
      }
        $scope.fuploading_banner_logo = 1;
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
                $scope.dataFrm.fund_banner_logo = resp.data.IMAGE_NAME;
                $scope.dataFrm.fund_banner_logo_disp = resp.data.PATH_TO_IMAGE;
                $scope.fuploading_banner_logo = 2;
            }
            else
            {
                $scope.fileuploaderror_fund_banner_logo = resp.data.MSG;
                $scope.fuploading_banner_logo = 3;
    
                $timeout(function(){
                    $scope.fuploading_banner_logo = 0;
                }, 3000);
    
            }
    
            //console.log(resp.data.PATH_TO_IMAGE);
    
        }, function (resp) {
            console.log('Error status: ' + resp.status);
        }, function (evt) {
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            //console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
            $scope.fuploading_banner_logo = 1;
        });
    };

}]);
