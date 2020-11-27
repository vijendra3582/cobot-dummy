angular.module('truemarkApp').directive('onlyAlphanumric', function () {
  return {
      restrict: 'A',
      require: '?ngModel',
      link: function (scope, element, attrs, modelCtrl) {
          modelCtrl.$parsers.push(function (inputValue) {
              if (inputValue == undefined) return '';
              var transformedInput = inputValue.replace(/[^0-9a-zA-Z-]/g, '');
              if (transformedInput !== inputValue) {
                  modelCtrl.$setViewValue(transformedInput);
                  modelCtrl.$render();
              }
              return transformedInput;
          });
      }
  };
});
angular.module('truemarkApp').controller('addFundFilesController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'FundDetail', 'Upload', '$filter', '$q', function($http, myConfig, $location, $routeParams, $scope, $timeout, FundDetail , Upload, $filter, $q){
    $scope.dataFrm = {};
    $scope.dataFrm.allRows = [];
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
    
    $scope.fullPageLoader = 1; 
    var promise = FundDetail.getFundDetail($routeParams.id); 
    var objList = {};
    objList.call = 'fund';
    objList.stype = 'getFundFiles';
    objList.fund_id = $routeParams.id;
    
    //console.log(objList);
    var promise_list =  $http({method: 'POST', url: 'cms/fund/files', data: objList});
     
    $q.all([promise, promise_list]).then(function(result){
        
        $scope.fullPageLoader = 0;
        $scope.submitProcess = 0;
     
        var objFund = result[0].data.data; 
        $scope.dataFrm.fund_id = objFund.id;
        $scope.dataFrm.fund_name = objFund.fund_name ? objFund.fund_name.replace(/\\/g,''): '';
        $scope.dataFrm.fund_ticker = objFund.fund_ticker ? objFund.fund_ticker.replace(/\\/g,'') : '';  
        $scope.dataFrm.is_outcome_product = objFund.is_outcome_product;
        //menu title ======================
        
        var objFiles = result[1].data.data;
        
        if(parseInt(objFiles.length) > parseInt(0)) {
            angular.forEach(objFiles, function(value, key) {
                 
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
               
               $scope.dataFrm.allRows.push(value);  
            });
              
        } else {             
            $scope.dataFrm.allRows.push({id:'', label_name: 'Term Sheet', file_path: '', status: 1, fuploading: 0, url_key: ''}); 
        }
    
        
        
        ///////////// file UPLOAD ====================================
        // upload on file select or drop
        $scope.upload = function (file, idx) {
            $scope.dataFrm.allRows[idx].file_link = "#";
            $scope.dataFrm.allRows[idx].fuploading = 1;
            
            if(!file || !file.name){
                $scope.dataFrm.allRows[idx].fileuploaderror = 'Unsupported file format';
                $scope.dataFrm.allRows[idx].fuploading = 3;
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
                        $scope.dataFrm.allRows[idx].file_path = resp.data.IMAGE_NAME;
                        $scope.dataFrm.allRows[idx].file_disp = resp.data.IMAGE_NAME;
                        $scope.dataFrm.allRows[idx].file_extension = resp.data.IMAGE_EXTENSION;
                        $scope.dataFrm.allRows[idx].fuploading = 2;
                    }
                    else
                    {
                        $scope.dataFrm.allRows[idx].fileuploaderror = resp.data.MSG;
                        $scope.dataFrm.allRows[idx].fuploading = 3;

                        $timeout(function(){
                            $scope.dataFrm.allRows[idx].fuploading = 0;
                        }, 3000);

                    }

                    //console.log(resp.data.PATH_TO_IMAGE);

                }, function (resp) {
                    console.log('Error status: ' + resp.status);
                }, function (evt) {
                    var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                    //console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
                    $scope.dataFrm.allRows[idx].fuploading = 1;
                });
            }  
        };
        
        
        $scope.removeFile = function(idx) {
            var c = confirm("Are you sure you wish to remove?");
            if(c)
            {                 
                if($scope.dataFrm.allRows[idx].id) {
                    $http({method: 'POST', url: 'cms/fund/files/removeFiles', data: {call: 'fund', stype:'removeFile', fund_id: $routeParams.id, id: $scope.dataFrm.allRows[idx].id}}).success(function(response){
                        //console.log(response);
                        $scope.dataFrm.allRows[idx].fuploading = 0;
                        $scope.dataFrm.allRows[idx].file_path = '';
                    });
                } else {
                    $scope.dataFrm.allRows[idx].fuploading = 0;
                    $scope.dataFrm.allRows[idx].file_path = '';
                } 
            } 
        };
         
        /////////// POSITION =====================
        $scope.posLoader = false;
        $scope.goUp = function(indx) {
            $scope.posLoader = true;
            var newIndx = parseInt(indx) - parseInt(1);
            var thirdObj = $scope.dataFrm.allRows[newIndx];
        
            $scope.dataFrm.allRows[newIndx] = $scope.dataFrm.allRows[indx];
            $scope.dataFrm.allRows[indx] = thirdObj;
        
            thirdObj = {};
        
            var secPosArray = [];
            angular.forEach($scope.dataFrm.allRows, function(objVal, key) {
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
            
        
            $scope.posLoader = false;
        
        
        
        };
        
        $scope.goDown = function(indx) {
            $scope.posLoader = true;
            var newIndx = parseInt(indx) + parseInt(1);
            var thirdObj = $scope.dataFrm.allRows[newIndx];
        
            $scope.dataFrm.allRows[newIndx] = $scope.dataFrm.allRows[indx];
            $scope.dataFrm.allRows[indx] = thirdObj;
        
            thirdObj = {};
        
            //console.log($scope.allSectionArray);
        
            var secPosArray = [];
            angular.forEach($scope.dataFrm.allRows, function(objVal, key) {
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
            
        
            $scope.posLoader = false;
        
        };
         
        $scope.submit = function() {
            $scope.submitProcess = 1;
            $scope.dataFrm.call = 'fund';
            $scope.dataFrm.stype = 'saveFundFiles';
            
        
            $http({method: 'POST', url: 'cms/fund/files/save', data: $scope.dataFrm}).success(function(response){
                  $scope.submitProcess = 2;
        
                  if(response.SUCCESS == '1')
                  {
                      $scope.submitProcessMsg = '&#x2714; Successfully Saved';
                      swal({
                          title:'Success',
                          text: "Successfully Saved",
                          icon:'success',
                          timer:3000
                      });
        
                  }
                  else
                  {
                      $scope.submitProcessMsg = '&#x2718; Sorry Cannot Process Your Request';
                  }
        
                  console.log(response);
                  $timeout(function(){
                      $scope.submitProcess = 0;
                      if(response.SUCCESS == '1') {
                          location.href = "#addFundFiles/" + $routeParams.id + "?A=" + Math.random();
                          //$scope.dataFrm = {};
                      }
                  }, 3000);
        
              });
        
        
        };
    
    
        
        $scope.deleteFile = function(idx) {
            
            if($scope.dataFrm.allRows[idx].id) {
                var id = $scope.dataFrm.allRows[idx].id;
                var c = confirm("Are you sure you wish to delete this?");
                if(c) {
                    
                    $scope.dataFrm.allRows[idx].loader = true;
                    // alert(idx + "===" + id); 
                    $http({method: 'POST', url: 'cms/fund/files/delete', data: {call: 'fund', stype:'deleteFundFile', fund_id: $routeParams.id, id: id}}).success(function(response){
                        $scope.dataFrm.allRows[idx].loader = false;
                        //console.log(response);
                        $scope.dataFrm.allRows.splice(idx, 1);
                         
                        if($scope.dataFrm.allRows.length == 0) {
                            $scope.addMoreFiles();
                        }
                    });
                }
            } else {
                $scope.dataFrm.allRows.splice(idx, 1);
            }
            
        };
    
        $scope.addMoreFiles = function() {
            $scope.dataFrm.allRows.push({id:'', label_name: '', file_path: '', status: 1, fuploading: 0, url_key: ''});    
        };
        
    
    });


}]);