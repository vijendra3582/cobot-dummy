angular.module('truemarkApp').controller('interimFundPageController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'Upload', '$filter', function($http, myConfig, $location, $routeParams, $scope, $timeout , Upload, $filter){

    $scope.dataFrm = {}; 
    $scope.dataFrm.allRowsFD = [];
    $scope.dataFrm.allRowsFF = [];
    $scope.fullPageLoader = 0; 

    var guid = function() {
      var s4 = function() {
        return Math.floor((1 + Math.random()) * 0x10000)
          .toString(16)
          .substring(1);
      }
      return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
        s4() + '-' + s4() + s4() + s4();
    }

   //if($routeParams.id) {
        $scope.fullPageLoader = 1; 

        var dataToSend = {};      
        var promise = $http({method: 'POST', url: 'cms/interimfund/edit', data: dataToSend});;

        promise.then(
            function(payload) {
                console.log(payload);

                if(parseInt(payload.data.length) > parseInt(0))
                {
                    //menu title ======================

                    $scope.dataFrm.status = payload.data.data.status == 0 ? false:true;
                    $scope.dataFrm.fund_name = payload.data.data.fund_name ? payload.data.data.fund_name.replace(/\\/g,'') : '';
                    $scope.dataFrm.sub_title = payload.data.data.sub_title ? payload.data.data.sub_title.replace(/\\/g,'') : '';
                    $scope.dataFrm.menu_title = payload.data.data.menu_title ? payload.data.data.menu_title.replace(/\\/g,'') : '';

                    $scope.dataFrm.fund_ticker = payload.data.data.fund_ticker ? payload.data.data.fund_ticker.replace(/\\/g,'') : '';
                    
                    if(payload.data.data.fund_description) {
                        $scope.dataFrm.fund_description = payload.data.data.fund_description ? payload.data.data.fund_description.replace(/\\/g,'') : '';
                    }

                    if(payload.data.data.fund_launch_description) {
                        $scope.dataFrm.fund_launch_description = payload.data.data.fund_launch_description ? payload.data.data.fund_launch_description.replace(/\\/g,'') : '';
                    }

                    $scope.dataFrm.fund_disclosure = payload.data.data.fund_disclosure ? payload.data.data.fund_disclosure.replace(/\\/g,'') : '';
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

                    $scope.dataFrm.url_key = payload.data.data.url_key;
                    $scope.dataFrm.meta_title = payload.data.data.meta_title;
                    $scope.dataFrm.meta_keyword = payload.data.data.meta_keyword;
                    $scope.dataFrm.meta_description = payload.data.data.meta_description;

                    /// fund detail 
                    if(payload.data.data.fund_data) {
                        $scope.dataFrm.allRowsFD = payload.data.data.fund_data || [];
                    }
                    /// fund documents
                    if(payload.data.data.fund_files) {
                        $scope.dataFrm.allRowsFF = payload.data.data.fund_files || [];

                        if(parseInt($scope.dataFrm.allRowsFF.length) > parseInt(0)) {
                            angular.forEach($scope.dataFrm.allRowsFF, function(value, key) {
                                 
                                if(parseInt(value.status) == parseInt(1)) {
                                    value.status = true;
                                } else {
                                    value.status = false;
                                }
                
                                value.file_path = '';
                                if(value.file_disp)
                                {
                                  value.file_disp = value.file_disp ? value.file_disp.replace(/\\/g,'') : '';
                                  value.fuploading = 2;
                                }
                                else
                                {
                                  value.file_disp = '';
                                  value.fuploading = 0;
                                }
                               
                            //    $scope.dataFrm.allRows.push(value);  
                            });
                              
                        }
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

    //} else {
        
    //} 

    $scope.submitProcess = 0;
    $scope.submitProcessMsg = '';

    $scope.submit = function() {
        $scope.submitProcess = 1; 
        $http({method: 'POST', url: 'cms/interimfund/save', data: $scope.dataFrm}).success(function(response){
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
                      window.location.reload();
                  }
              }, 3000);

          });
    };


    // Editor options.
    $scope.options = {
        language: 'en',
        allowedContent: true,
        entities: false,
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

            $http({method: 'POST', url:'cms/interimfund/removeImage', data: {call: 'interimfund', stype:'removeImage', field:field, file:file}}).success(function(response){
                if (response.SUCCESS==1) {
                    $scope['fuploading_'+field] = 0;
                    $scope.dataFrm[field] = '';
                    $scope.dataFrm[field + '_disp'] = '';
                } else {
                    $scope['fuploading_'+field] = 2;
                }
            });
        


          }
        //else
          //alert("Pleast refresh the page");
    };



    ////////////////// fund details =======================
    
    // $scope.dataFrm.allRowsFD.push({id:'', data_type: 'FUND_INCEPTION', data_head: 'Fund Inception', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1}); 
    // $scope.dataFrm.allRowsFD.push({id:'', data_type: 'TICKER', data_head: 'Ticker', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1});
    // $scope.dataFrm.allRowsFD.push({id:'', data_type: 'PRIMARY_EXCHANGE', data_head: 'Primary Exchange', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1});
    // $scope.dataFrm.allRowsFD.push({id:'', data_type: 'CUSIP', data_head: 'CUSIP', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1});
    // $scope.dataFrm.allRowsFD.push({id:'', data_type: 'IOPV_SYMBOL', data_head: 'IOPV Symbol', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1});
    // $scope.dataFrm.allRowsFD.push({id:'', data_type: 'NAV_SYMBOL', data_head: 'NAV Symbol', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1});
    // $scope.dataFrm.allRowsFD.push({id:'', data_type: 'EXPENSE_RATIO', data_head: 'Expense Ratio', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1});
    // $scope.dataFrm.allRowsFD.push({id:'', data_type: '30_DAY_SEC_YIELD', data_head: '30 Day SEC Yield *', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1});


    /////////// POSITION =====================
    $scope.posLoaderFD = false;
    $scope.goUpFD = function(indx) {
        $scope.posLoaderFD = true;
        var newIndx = parseInt(indx) - parseInt(1);
        var thirdObj = $scope.dataFrm.allRowsFD[newIndx];
    
        $scope.dataFrm.allRowsFD[newIndx] = $scope.dataFrm.allRowsFD[indx];
        $scope.dataFrm.allRowsFD[indx] = thirdObj;
        thirdObj = {};
        $scope.posLoaderFD = false;
    };
    
    $scope.goDownFD = function(indx) {
        $scope.posLoaderFD = true;
        var newIndx = parseInt(indx) + parseInt(1);
        var thirdObj = $scope.dataFrm.allRowsFD[newIndx];
         
        $scope.dataFrm.allRowsFD[newIndx] = $scope.dataFrm.allRowsFD[indx];
        $scope.dataFrm.allRowsFD[indx] = thirdObj;
    
        thirdObj = {};
     
        $scope.posLoaderFD = false;
    
    };

    $scope.addMoreFD = function() {
        $scope.dataFrm.allRowsFD.push({id:'', data_type: '', data_head: '', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1});
    };

    $scope.editorConfig = {
	    sanitize: false,
	    toolbar: [{ name: 'basicStyling', items: ['bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript']}]
    };
    
    $scope.deleteFD = function(idx) {    
        $scope.dataFrm.allRowsFD.splice(idx, 1);
    };
    

    //// fund files ==================================== 
    // upload on file select or drop
    $scope.uploadFF = function (file, idx) {
        $scope.dataFrm.allRowsFF[idx].file_link = "#";
        $scope.dataFrm.allRowsFF[idx].fuploading = 1;
        
        if(!file || !file.name){
            $scope.dataFrm.allRowsFF[idx].fileuploaderror = 'Unsupported file format';
            $scope.dataFrm.allRowsFF[idx].fuploading = 3;
            // return false;
        }else{
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
                    $scope.dataFrm.allRowsFF[idx].file_path = resp.data.IMAGE_NAME;
                    $scope.dataFrm.allRowsFF[idx].file_disp = resp.data.IMAGE_NAME;
                    $scope.dataFrm.allRowsFF[idx].file_extension = resp.data.IMAGE_EXTENSION;
                    $scope.dataFrm.allRowsFF[idx].fuploading = 2;
                }
                else
                {
                    $scope.dataFrm.allRowsFF[idx].fileuploaderror = resp.data.MSG;
                    $scope.dataFrm.allRowsFF[idx].fuploading = 3;

                    $timeout(function(){
                        $scope.dataFrm.allRowsFF[idx].fuploading = 0;
                    }, 3000);

                }

                //console.log(resp.data.PATH_TO_IMAGE);

            }, function (resp) {
                console.log('Error status: ' + resp.status);
            }, function (evt) {
                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                //console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
                $scope.dataFrm.allRowsFF[idx].fuploading = 1;
            });
        }  
    };
    
    
    $scope.removeFileFF = function(idx) {
        var c = confirm("Are you sure you wish to remove?");
        if(c)
        {                 
            if($scope.dataFrm.allRowsFF[idx].id) {
                $http({method: 'POST', url: 'cms/interimfund/files/removeFiles', data: {call: 'interimfund', stype:'removeFile', fund_id: $routeParams.id, id: $scope.dataFrm.allRowsFF[idx].id}}).success(function(response){
                    //console.log(response);
                    $scope.dataFrm.allRowsFF[idx].fuploading = 0;
                    $scope.dataFrm.allRowsFF[idx].file_path = '';
                });
            } else {
                $scope.dataFrm.allRowsFF[idx].fuploading = 0;
                $scope.dataFrm.allRowsFF[idx].file_path = '';
            } 
        } 
    };
     
    /////////// POSITION =====================
    $scope.posLoaderFF = false;
    $scope.goUpFF = function(indx) {
        $scope.posLoaderFF = true;
        var newIndx = parseInt(indx) - parseInt(1);
        var thirdObj = $scope.dataFrm.allRowsFF[newIndx];
    
        $scope.dataFrm.allRowsFF[newIndx] = $scope.dataFrm.allRowsFF[indx];
        $scope.dataFrm.allRowsFF[indx] = thirdObj;
    
        thirdObj = {};
    
        var secPosArray = [];
        angular.forEach($scope.dataFrm.allRowsFF, function(objVal, key) {
            //this.push(key + ': ' + value);
            
            secPosArray.push({
                id: objVal.id,
                index_position: key
            });
    
        });
    
        
        // $http({method: 'POST', url: 'cms/fund/files/saveposition', data: {call: 'index', stype:'savePosition', data: secPosArray}}).success(function(response){
        //     //console.log(response);
        //     $scope.posLoader = false;
        // });
        
    
        $scope.posLoaderFF = false;
    
    
    
    };
    
    $scope.goDownFF = function(indx) {
        $scope.posLoaderFF = true;
        var newIndx = parseInt(indx) + parseInt(1);
        var thirdObj = $scope.dataFrm.allRowsFF[newIndx];
    
        $scope.dataFrm.allRowsFF[newIndx] = $scope.dataFrm.allRowsFF[indx];
        $scope.dataFrm.allRowsFF[indx] = thirdObj;
    
        thirdObj = {};
    
        //console.log($scope.allSectionArray);
    
        var secPosArray = [];
        angular.forEach($scope.dataFrm.allRowsFF, function(objVal, key) {
            //this.push(key + ': ' + value);
            //console.log(key + ': ' + objVal.section_id);
            secPosArray.push({
                index_id: objVal.index_id,
                index_position: key
            });
    
        });
    
        
        // $http({method: 'POST', url: 'cms/fund/files/saveposition', data: {call: 'index', stype:'savePosition', data: secPosArray}}).success(function(response){
        //     //console.log(response);
        //     $scope.posLoader = false;
        // });
        
    
        $scope.posLoaderFF = false;
    
    };

    $scope.deleteFileFF = function(idx) {
            
        if($scope.dataFrm.allRowsFF[idx].id) {
            var id = $scope.dataFrm.allRowsFF[idx].id;
            var c = confirm("Are you sure you wish to delete this?");
            if(c) {
                
                $scope.dataFrm.allRowsFF[idx].loader = true;
                // alert(idx + "===" + id); 
                $http({method: 'POST', url: 'cms/interimfund/files/delete', data: {call: 'fund', stype:'deleteFundFile', fund_id: $routeParams.id, id: id}}).success(function(response){
                    $scope.dataFrm.allRowsFF[idx].loader = false;
                    //console.log(response);
                    $scope.dataFrm.allRowsFF.splice(idx, 1);
                     
                    if($scope.dataFrm.allRowsFF.length == 0) {
                        $scope.addMoreFilesFF();
                    }
                });
            }
        } else {
            $scope.dataFrm.allRowsFF.splice(idx, 1);
        }
        
    };

    $scope.addMoreFilesFF = function() {
        $scope.dataFrm.allRowsFF.push({id:'', label_name: '', file_path: '', status: 1, fuploading: 0, url_key: ''});    
    };


}]);
