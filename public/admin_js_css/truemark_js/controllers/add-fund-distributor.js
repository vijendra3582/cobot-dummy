angular.module('truemarkApp').controller('addFundDistributionController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'FundDetail', 'Upload', '$filter', '$q', function($http, myConfig, $location, $routeParams, $scope, $timeout, FundDetail , Upload, $filter, $q){
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
      $scope.fuploading = 0;


      var promise = FundDetail.getFundDetail($routeParams.id);

      promise.then(
          function(payload) {
              console.log(payload);
              if(parseInt(payload.data.length) > parseInt(0))
              { 
                  $scope.dataFrm.fund_id = payload.data.data.id;
                  $scope.dataFrm.fund_name = payload.data.data.fund_name ? payload.data.data.fund_name.replace(/\\/g,'') : '';
                  $scope.dataFrm.fund_ticker = payload.data.data.fund_ticker ? payload.data.data.fund_ticker.replace(/\\/g,'') : '';
                  $scope.dataFrm.f_active_fund_distribution = (parseInt(payload.data.data.f_active_fund_distribution) == parseInt(0) ? false:true);
                  $scope.dataFrm.is_outcome_product = (parseInt(payload.data.data.is_outcome_product) == parseInt(0) ? false:true);
                  if(payload.data.data.distribution_schedule_file != null && payload.data.data.distribution_schedule_file != "") {

                        var fname = payload.data.data.distribution_schedule_file.replace(/\\/g,'');
                        //$scope.dataFrm.factsheet_file = fname;
                        $scope.distribution_schedule_file_disp = fname;
                        $scope.fuploading = 2;
                        $scope.distribution_schedule_file_extension = fname.substr(fname.lastIndexOf('.')+1);

                        $scope.distribution_schedule_file_link = payload.data.data.distribution_schedule_file_link ? payload.data.data.distribution_schedule_file_link.replace(/\\/g,'') : '';
                  }
                 
              }
              else
              {
                    //location.href = "fund.php";
                    $location.path('/welcome');
              }


          },
          function(errorPayload) {
              console.log('failure loading fund', errorPayload);
          }


      );

      var objList = {};
      objList.call = 'fund';
      objList.stype = 'listFundDistribution';
      objList.fund_id = $routeParams.id;

      console.log(objList);

      var promise_list = $http({method: 'POST', url: 'cms/fund/distribution', data: objList});

       promise_list.then(
          function(payload) {
              console.log(payload.data.data);
              $scope.dataFrm.allRows = payload.data.data;
          },
          function(errorPayload) {
              console.log('failure loading fund', errorPayload);
          }
      );



    $q.all([promise, promise_list]).then(function(result){
         $scope.fullPageLoader = 0;
         $scope.submitProcess = 0;
        
          if(parseInt($scope.dataFrm.allRows.length) == parseInt(0)) { 
            $scope.dataFrm.allRows.push({id:'', ex_date: '', record_date: '', payable_date: '', amount: ''});
          }  
          $scope.addMore = function() {
             $scope.dataFrm.allRows.push({id:'', ex_date: '', record_date: '', payable_date: '', amount: ''});
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
                //console.log(key + ': ' + objVal.section_id);
                secPosArray.push({
                    id: objVal.id,
                    fund_position: key
                });

            });

            /*
            $http({method: 'POST', url: myConfig.ajax_url, data: {call: 'fund', stype:'savePosition', data: secPosArray}}).success(function(response){
                //console.log(response);
                $scope.posLoader = false;
            });
            */

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
                    fund_id: objVal.id,
                    fund_position: key
                });

            });

            /*
            $http({method: 'POST', url: myConfig.ajax_url, data: {call: 'fund', stype:'savePosition', data: secPosArray}}).success(function(response){
                //console.log(response);
                $scope.posLoader = false;
            });
            */

            $scope.posLoader = false;

        };

        $scope.deleteData = function(idx) {
            //$scope.dataFrm.allRows.splice(idx, 1);
            if($scope.dataFrm.allRows[idx].id) {
            $scope.fullPageLoader = 1;
              var id = $scope.dataFrm.allRows[idx].id;
              var c = confirm("Are you sure you wish to delete this?");
              if(c) {
                  
                  $scope.dataFrm.allRows[idx].loader = true;
                  // alert(idx + "===" + id); 
                  $http({method: 'POST', url: 'cms/fund/distribution/delete', data: {call: 'fund', stype:'deleteFundDetail', fund_id: $routeParams.id, id: id}}).success(function(response){
                      $scope.dataFrm.allRows[idx].loader = false;
                      //console.log(response);
                      $scope.dataFrm.allRows.splice(idx, 1);
                       
                      if($scope.dataFrm.allRows.length == 0) {
                          $scope.addMore();
                      }
                  });
              }
          } else {
              $scope.dataFrm.allRows.splice(idx, 1);
          }
          $scope.fullPageLoader = 0;
        };
        


        $scope.submit = function() {
            $scope.submitProcess = 1;
            $scope.dataFrm.call = 'fund';
            $scope.dataFrm.stype = 'saveFundDistribution';

            $http({method: 'POST', url: 'cms/fund/distribution/save', data: $scope.dataFrm}).success(function(response){
                  $scope.submitProcess = 2;

                  if(response.SUCCESS == '1')
                  {
                      $scope.submitProcessMsg = '&#x2714; Successfully Saved';
                      swal({
                        title:'Success',
                        text:'Successfully Saved',
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
                            location.href = "#/addFundDistribution/" + $routeParams.id + "/?A=" + Math.random();
                          //$location.path("/listIndex/1");
                          //$scope.dataFrm = {};
                      }
                  }, 3000);

              });


        };


        $scope.checkAddMore = function() {
          if($scope.dataFrm.allRows.length > 0){
            if($scope.dataFrm.allRows[$scope.dataFrm.allRows.length - 1].ex_date || $scope.dataFrm.allRows[$scope.dataFrm.allRows.length - 1].record_date || $scope.dataFrm.allRows[$scope.dataFrm.allRows.length - 1].payable_date || $scope.dataFrm.allRows[$scope.dataFrm.allRows.length - 1].amount)
            {
                return false;
            }
            else
            {
                return true;
            }
          }else{
            return false;
          }
        };





       

    });


    $scope.activateDeactivateFundDistribution = function() {
        //console.log($scope.dataFrm.f_active_fund_holdings);

        $http({method: 'POST', url: 'cms/fund/distribution/activatateFundDistribution', data: {call: 'fund', stype:'activateDeactivateFundDistribution', fund_id: $routeParams.id, f_active_fund_distribution: $scope.dataFrm.f_active_fund_distribution}}).success(function(response){
              //console.log(response);
              swal({
                title:'Success',
                icon:'success',
                timer:3000
              });
        });
    };




    // upload on file select or drop
    $scope.uploadHoldingsFile = function (file) {
            if(!file.name){
                return false;
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
                $scope.dataFrm.distribution_schedule_file = resp.data.IMAGE_NAME;
                $scope.distribution_schedule_file_disp = resp.data.IMAGE_NAME;
                $scope.distribution_schedule_file_extension = resp.data.IMAGE_EXTENSION;
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


    $scope.removeDistributionScheduleFile = function() {
        var c = confirm("Are you sure you wish to remove?");
        if(c)
        {
            if($routeParams.id) {
                $http({method: 'POST', url: 'cms/fund/distribution/removeScheduleFile', data: {call: 'fund', stype:'removeDistributionScheduleFile', fund_id: $routeParams.id}}).success(function(response){
                    //console.log(response);
                    $scope.fuploading = 0;
                    $scope.dataFrm.distribution_schedule_file = '';
                    $scope.distribution_schedule_file_link = '';
                    $scope.distribution_schedule_file_disp = '';
                });
            } else {
                $scope.fuploading = 0;
                $scope.dataFrm.distribution_schedule_file = '';
                $scope.distribution_schedule_file_link = '';
                $scope.distribution_schedule_file_disp = '';
            }

        }


    };




}]);
