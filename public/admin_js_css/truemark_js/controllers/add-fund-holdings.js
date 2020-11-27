angular.module('truemarkApp').controller('addFundHoldingsController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'FundDetail', 'Upload', '$filter', '$q', function($http, myConfig, $location, $routeParams, $scope, $timeout, FundDetail , Upload, $filter, $q){
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
              // console.log(payload);
              if(parseInt(payload.data.length) > parseInt(0))
              { 
                  $scope.dataFrm.fund_id = payload.data.data.id;
                  $scope.dataFrm.fund_name = payload.data.data.fund_name ? payload.data.data.fund_name.replace(/\\/g,'') : '';
                  $scope.dataFrm.fund_ticker =payload.data.data.fund_ticker ? payload.data.data.fund_ticker.replace(/\\/g,'') : '';
                  $scope.dataFrm.f_override_fund_holdings = (parseInt(payload.data.data.f_override_fund_holdings) == parseInt(0) ? false:true);
                  $scope.dataFrm.f_active_fund_holdings = (parseInt(payload.data.data.f_active_fund_holdings) == parseInt(0) ? false:true);
                  $scope.dataFrm.is_outcome_product = (parseInt(payload.data.data.is_outcome_product) == parseInt(0) ? false:true);
                  if(payload.data.data.fund_holdings_as_of_date != null && payload.data.data.fund_holdings_as_of_date != "0000-00-00") {
                        $scope.dataFrm.fund_holdings_as_of_date = $filter('date')(payload.data.data.fund_holdings_as_of_date, "MM-dd-yyyy"); // for conversion to string
                  } else {
                        $scope.dataFrm.fund_holdings_as_of_date = '';
                  }

                  if(payload.data.data.holdings_file) {
                        $scope.dataFrm.holdings_file = '';
                        var fname = payload.data.data.holdings_file ? payload.data.data.holdings_file.replace(/\\/g,'') : '';
                        //$scope.dataFrm.factsheet_file = fname;
                        $scope.holdings_file_disp = fname;
                        $scope.fuploading = 2;
                        $scope.holdings_file_extension = fname.substr(fname.lastIndexOf('.')+1);

                        $scope.holdings_file_link = payload.data.data.holdings_file_link ? payload.data.data.holdings_file_link.replace(/\\/g,'') : '';
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
      objList.stype = 'listFundHoldings';
      objList.fund_id = $routeParams.id;

      // console.log(objList);

      var promise_list = $http({method: 'POST', url: 'cms/fund/holdings', data: objList});

       promise_list.then(
          function(payload) {
              // console.log(payload.data.data);
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
            $scope.dataFrm.allRows.push({id:'', percentage_of_net_assets: '', name: '', identifier: '',cusip: '', shares_held: '', market_value: ''});
         }

         $scope.addMore = function() {
             $scope.dataFrm.allRows.push({id:'', percentage_of_net_assets: '', name: '', identifier: '',cusip: '', shares_held: '', market_value: ''});
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
            // $scope.dataFrm.allRows.splice(idx, 1);
          if($scope.dataFrm.allRows[idx].id) {
          $scope.fullPageLoader = 1;
            var id = $scope.dataFrm.allRows[idx].id;
            var c = confirm("Are you sure you wish to delete this?");
            if(c) {
                
                $scope.dataFrm.allRows[idx].loader = true;
                // alert(idx + "===" + id); 
                $http({method: 'POST', url: 'cms/fund/holdings/delete', data: {call: 'fund', stype:'deleteFundDetail', fund_id: $routeParams.id, id: id}}).success(function(response){
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
            $scope.dataFrm.stype = 'saveFundHoldings';

            $http({method: 'POST', url: 'cms/fund/holdings/save', data: $scope.dataFrm}).success(function(response){
                  $scope.submitProcess = 2;

                  if(response.SUCCESS == '1')
                  {
                      $scope.submitProcessMsg = '&#x2714; Successfully Saved';
                      swal({
                        title:"Success",
                        text:"Successfully Saved",
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
                            location.href = "#/addFundHoldings/" + $routeParams.id + "/?A=" + Math.random();
                          //$location.path("/listIndex/1");
                          //$scope.dataFrm = {};
                      }
                  }, 3000);

              });


        };


        $scope.checkAddMore = function() {
          if($scope.dataFrm.allRows.length > 0){
            if($scope.dataFrm.allRows[$scope.dataFrm.allRows.length - 1].name || $scope.dataFrm.allRows[$scope.dataFrm.allRows.length - 1].ticker || $scope.dataFrm.allRows[$scope.dataFrm.allRows.length - 1].weight || $scope.dataFrm.allRows[$scope.dataFrm.allRows.length - 1].yield)
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





        ///////////// FACTSHEET UPLOAD ====================================

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
                    $scope.dataFrm.holdings_file = resp.data.IMAGE_NAME;
                    $scope.holdings_file_disp = resp.data.IMAGE_NAME;
                    $scope.holdings_file_extension = resp.data.IMAGE_EXTENSION;
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


        $scope.removeHoldingsFile = function() {
            var c = confirm("Are you sure you wish to remove?");
            if(c)
            {
                if($routeParams.id) {
                    $http({method: 'POST', url: 'cms/fund/holdings/removeHoldingFiles', data: {call: 'fund', stype:'removeHoldingsFile', fund_id: $routeParams.id}}).success(function(response){
                        //console.log(response);
                        $scope.fuploading = 0;
                        $scope.dataFrm.holdings_file = '';
                        $scope.holdings_file_disp = '';
                        $scope.holdings_file_link = '';
                    });
                } else {
                    $scope.fuploading = 0;
                    $scope.dataFrm.holdings_file = '';
                    $scope.holdings_file_disp = '';
                    $scope.holdings_file_link = '';
                }

            }


        };

    });


    $scope.activateDeactivateFundHoldings = function() {
        //console.log($scope.dataFrm.f_active_fund_holdings);

        $http({method: 'POST', url: 'cms/fund/holdings/activateFundHolding', data: {call: 'fund', stype:'activateDeactivateFundHoldings', fund_id: $routeParams.id, f_active_fund_holdings: $scope.dataFrm.f_active_fund_holdings}}).success(function(response){
              // console.log(response);
              swal({
                title:'Success',
                icon:'success',
                timer:3000
              });
        });
    };






}]);
