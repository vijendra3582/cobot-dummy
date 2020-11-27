angular.module('truemarkApp').controller('outcomePeriodValuesController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'FundDetail', 'Upload', '$filter', '$q', function($http, myConfig, $location, $routeParams, $scope, $timeout, FundDetail , Upload, $filter, $q){
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
                  $scope.dataFrm.display_outcome_period_values = (parseInt(payload.data.data.display_outcome_period_values) == parseInt(0) ? false:true);
                  $scope.dataFrm.outcome_period_values_text = payload.data.data.outcome_period_values_text ? payload.data.data.outcome_period_values_text.replace(/\\/g,'') : '';  
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
      objList.stype = 'listFundOutcomePeriod';
      objList.fund_id = $routeParams.id;

      console.log(objList);

      var promise_list = $http({method: 'POST', url: 'cms/fund/outcome-period', data: objList});

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
            $scope.dataFrm.allRows.push({id:'', etf_starting_nav_period_return: '', spx_index_reference_price: '', downside_buffer: '', expected_upside_participation: '',days_remaining:''});
          }  
          $scope.addMore = function() {
             $scope.dataFrm.allRows.push({id:'', etf_starting_nav_period_return: '', spx_index_reference_price: '', downside_buffer: '', expected_upside_participation: '',days_remaining:''});
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
                    position: key
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
                    id: objVal.id,
                    position: key
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
                    $http({method: 'POST', url: 'cms/fund/outcome-period/delete', data: {call: 'fund', stype:'delete', fund_id: $routeParams.id, id: id}}).success(function(response){
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
                if($scope.dataFrm.allRows.length == 0) {
                    $scope.addMore();
                }
            }
          $scope.fullPageLoader = 0;
        };
        


        $scope.submit = function() {
            $scope.submitProcess = 1;
            $scope.dataFrm.call = 'fund';
            $scope.dataFrm.stype = 'saveFundOutcomeValues';

            $http({method: 'POST', url: 'cms/fund/outcome-period/save', data: $scope.dataFrm}).success(function(response){
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
                            location.href = "#/outcomePeriodValues/" + $routeParams.id + "/?A=" + Math.random();
                          //$location.path("/listIndex/1");
                          //$scope.dataFrm = {};
                      }
                  }, 3000);

              });


        };


        $scope.checkAddMore = function() {
           
          if($scope.dataFrm.allRows.length > 0){
            if($scope.dataFrm.allRows[$scope.dataFrm.allRows.length - 1].etf_starting_nav_period_return || $scope.dataFrm.allRows[$scope.dataFrm.allRows.length - 1].spx_index_reference_price || $scope.dataFrm.allRows[$scope.dataFrm.allRows.length - 1].downside_buffer || $scope.dataFrm.allRows[$scope.dataFrm.allRows.length - 1].expected_upside_participation || $scope.dataFrm.allRows[$scope.dataFrm.allRows.length - 1].days_remaining)
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


    $scope.activateOutcomePeriod = function() {
        //console.log($scope.dataFrm.f_active_fund_holdings);

        $http({method: 'POST', url: 'cms/fund/outcome-period/activateOutcome', data: {call: 'fund', stype:'activateOutcomePeriod', fund_id: $routeParams.id, display_outcome_period_values: $scope.dataFrm.display_outcome_period_values}}).success(function(response){
              //console.log(response);
              swal({
                title:'Success',
                icon:'success',
                timer:3000
              });
        });
    };






}]);
