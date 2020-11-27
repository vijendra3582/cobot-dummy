angular.module('truemarkApp').controller('addPerformanceController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'FundDetail', 'Upload', '$filter', '$q', function($http, myConfig, $location, $routeParams, $scope, $timeout, FundDetail , Upload, $filter, $q){
      $scope.dataFrm = {};
      $scope.dataFrmSection = {};
      // $scope.dataFrm.allRowsHeaders = {};
      $scope.dataFrm.allRows = new Array();
      $scope.fullPageLoader = 0;
      $scope.toTimestamp = function(date) {
          return new Date(date).getTime();
      };

      $scope.global_f_title_asset_allocation = '';
       
      $scope.fullPageLoader = 1;


      var promise = FundDetail.getFundDetail($routeParams.id);

      promise.then(
          function(payload) {
              //console.log(payload);
              if(parseInt(payload.data.length) > parseInt(0))
              {
                  //menu title ======================
                  //$scope.global_f_title_asset_allocation = payload.data.data.f_title_asset_allocation.replace(/\\/g,''); 

                  $scope.dataFrm.fund_id = payload.data.data.id;
                  $scope.dataFrm.fund_name = payload.data.data.fund_name ? payload.data.data.fund_name.replace(/\\/g,'') : '';
                  $scope.dataFrm.fund_ticker = payload.data.data.fund_ticker ? payload.data.data.fund_ticker.replace(/\\/g,'') : '';
                  $scope.dataFrm.f_override_performance = (parseInt(payload.data.data.f_override_performance) == parseInt(0) ? false:true);
                  $scope.dataFrm.f_active_performance = (parseInt(payload.data.data.f_active_performance) == parseInt(0) ? false:true);
                  $scope.dataFrm.is_outcome_product = (parseInt(payload.data.data.is_outcome_product) == parseInt(0) ? false:true);
                  $scope.dataFrm.calendar_yr_perfromance_display = (parseInt(payload.data.data.calendar_yr_perfromance_display) == parseInt(0) ? false:true);
                  $scope.dataFrm.cumulative_performance_display = (parseInt(payload.data.data.cumulative_performance_display) == parseInt(0) ? false:true);
                   

                  if(payload.data.data.performance_as_of_text) {
                        $scope.dataFrm.performance_as_of_text = payload.data.data.performance_as_of_text ? payload.data.data.performance_as_of_text.replace(/\\/g,'') : '';
                  }

                  if(payload.data.data.cumulative_performance_text) {
                        $scope.dataFrm.cumulative_performance_text = payload.data.data.cumulative_performance_text ? payload.data.data.cumulative_performance_text.replace(/\\/g,'') : '';
                  }

                  if(payload.data.data.calendar_yr_preformance_text) {
                        $scope.dataFrm.calendar_yr_preformance_text = payload.data.data.calendar_yr_preformance_text ? payload.data.data.calendar_yr_preformance_text.replace(/\\/g,'') : '';
                  }

                  /*if(payload.data.data[0].performance_as_of_date != "0000-00-00") {
                        $scope.dataFrm.performance_as_of_date = $filter('date')(payload.data.data[0].performance_as_of_date, "MM-dd-yyyy"); // for conversion to string
                  } else {
                        $scope.dataFrm.performance_as_of_date = '';
                  }
                  */
                  /*
                  if(payload.data.data[0].performance_as_of_date_quarterly != "0000-00-00") {
                        $scope.dataFrm.performance_as_of_date_quarterly = $filter('date')(payload.data.data[0].performance_as_of_date_quarterly, "MM-dd-yyyy"); // for conversion to string
                  } else {
                        $scope.dataFrm.performance_as_of_date_quarterly = '';
                  }
                  */
                  
                  // if(payload.data.data.performance_expense_ratio) {
                  //       $scope.dataFrm.performance_expense_ratio = payload.data.data.performance_expense_ratio;
                  // }
                  
                  // if(payload.data.data.performance_available_after) {
                  //       $scope.dataFrm.performance_available_after = payload.data.data.performance_available_after;
                  // }
                  
                  // if(payload.data.data.performance_heading) {
                  //       $scope.dataFrm.performance_heading = payload.data.data.performance_heading ? payload.data.data.performance_heading.replace(/\\/g,'') : '';
                  // }

                  // if(payload.data.data.monthly_performance_text) {
                  //       $scope.dataFrm.monthly_performance_text = payload.data.data.monthly_performance_text ? payload.data.data.monthly_performance_text.replace(/\\/g,'') :'';
                  // }

                  // if(payload.data.data.quarterly_performance_text) {
                  //       $scope.dataFrm.quarterly_performance_text = payload.data.data.quarterly_performance_text ? payload.data.data.quarterly_performance_text.replace(/\\/g,'') : '';
                  // }
                  
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

    // $scope.fullPageLoader = 0;
    // $scope.submitProcess = 0;

    $scope.dataFrm.allRows = new Array();
    $scope.dataFrm.allRowsMonthly = new Array();

    $scope.addMoreRows = function(period = 'CALENDAR_YR') {

        if(period == 'CUMULATIVE') {

            if($scope.dataFrm.allRowsMonthly.length > 0) {
                /// clone last row
                var c = angular.copy($scope.dataFrm.allRowsMonthly[$scope.dataFrm.allRowsMonthly.length - 1]);

                angular.forEach(c.colArray, function(v, i){ 
                    v.value = '';
                });

                $scope.dataFrm.allRowsMonthly.push(c);
            } else {
                $scope.dataFrm.allRowsMonthly.push({rtype: 'CUMULATIVE_RETURNS', colArray: new Array()});
                $scope.addMoreColumns(period);
            }

        } else {
            if($scope.dataFrm.allRows.length > 0) {
                /// clone last row
                var c = angular.copy($scope.dataFrm.allRows[$scope.dataFrm.allRows.length - 1]);

                angular.forEach(c.colArray, function(v, i){ 
                    v.value = '';
                });

                $scope.dataFrm.allRows.push(c);
            } else {
                $scope.dataFrm.allRows.push({rtype: 'CALENDAR_YR_RETURNS', colArray: new Array()});
                $scope.addMoreColumns(period);
            }
        }
        
    };

    $scope.addMoreColumns = function(period = 'CALENDAR_YR') { 
        if(period == 'CUMULATIVE') {
            angular.forEach($scope.dataFrm.allRowsMonthly, function(v, i){ 
                v.colArray.push({value: ''}); 
            });
        } else {
            angular.forEach($scope.dataFrm.allRows, function(v, i){ 
                v.colArray.push({value: ''}); 
            });    
        }
        
    };
 

    $scope.removeRow = function(idx, period = 'CALENDAR_YR') {

        if(period == 'CUMULATIVE') {
            $scope.dataFrm.allRowsMonthly.splice(idx, 1);

            if($scope.dataFrm.allRowsMonthly.length == 0) {
                $scope.addMoreRows(period);
            }
        } else {
            $scope.dataFrm.allRows.splice(idx, 1);

            if($scope.dataFrm.allRows.length == 0) {
                $scope.addMoreRows(period);
            }
        }
    };

    $scope.removeColumn = function(idx, period = 'CALENDAR_YR') {

        if(period == 'CUMULATIVE') {
            console.log(idx);
            var is_col_empty = false;
            angular.forEach($scope.dataFrm.allRowsMonthly, function(v, i){ 
                v.colArray.splice(idx, 1); 

                if(v.colArray.length == 0) {
                    is_col_empty = true;
                }
            });

            if(is_col_empty) {
                $scope.addMoreColumns(period);
            }
        } else {
            console.log(idx);
            var is_col_empty = false;
            angular.forEach($scope.dataFrm.allRows, function(v, i){ 
                v.colArray.splice(idx, 1); 

                if(v.colArray.length == 0) {
                    is_col_empty = true;
                }
            });

            if(is_col_empty) {
                $scope.addMoreColumns(period);
            }
        }

 
    };



    $scope.posLoader = false;
    $scope.posLoaderMonthly = false;
    $scope.goUp = function(indx, period = 'CALENDAR_YR') {
        if(period == 'CUMULATIVE') {
            $scope.posLoaderMonthly = true;
            var newIndx = parseInt(indx) - parseInt(1);
            var thirdObj = $scope.dataFrm.allRowsMonthly[newIndx];
        
            $scope.dataFrm.allRowsMonthly[newIndx] = $scope.dataFrm.allRowsMonthly[indx];
            $scope.dataFrm.allRowsMonthly[indx] = thirdObj;
        
            thirdObj = {};
             
        
            $scope.posLoaderMonthly = false;
        } else {
            $scope.posLoader = true;
            var newIndx = parseInt(indx) - parseInt(1);
            var thirdObj = $scope.dataFrm.allRows[newIndx];
        
            $scope.dataFrm.allRows[newIndx] = $scope.dataFrm.allRows[indx];
            $scope.dataFrm.allRows[indx] = thirdObj;
        
            thirdObj = {};
             
        
            $scope.posLoader = false;
        }
    
    
    
    };
    
    $scope.goDown = function(indx, period = 'CALENDAR_YR') {

        if(period == 'CUMULATIVE') {
            $scope.posLoaderMonthly = true;
            var newIndx = parseInt(indx) + parseInt(1);
            var thirdObj = $scope.dataFrm.allRowsMonthly[newIndx];
             
            $scope.dataFrm.allRowsMonthly[newIndx] = $scope.dataFrm.allRowsMonthly[indx];
            $scope.dataFrm.allRowsMonthly[indx] = thirdObj;
        
            thirdObj = {};
         
            $scope.posLoaderMonthly = false;
        } else {
            $scope.posLoader = true;
            var newIndx = parseInt(indx) + parseInt(1);
            var thirdObj = $scope.dataFrm.allRows[newIndx];
             
            $scope.dataFrm.allRows[newIndx] = $scope.dataFrm.allRows[indx];
            $scope.dataFrm.allRows[indx] = thirdObj;
        
            thirdObj = {};
         
            $scope.posLoader = false;
        }
    
    };


    $scope.posLoaderTop = false;
    $scope.posLoaderTopMonthly = false;
    $scope.goLeft = function(indx, period = 'CALENDAR_YR') {

        if(period == 'CUMULATIVE') {
            $scope.posLoaderTopMonthly = true;
            

            angular.forEach($scope.dataFrm.allRowsMonthly, function(v, i){ 
                
                var newIndx = parseInt(indx) - parseInt(1);
                var thirdObj = v.colArray[newIndx];

                if(newIndx >= 0) {
                    v.colArray[newIndx] = v.colArray[indx];
                    v.colArray[indx] = thirdObj;  
                }

                thirdObj = {};
     
            });

            $scope.posLoaderTopMonthly = false;

        } else {
            $scope.posLoaderTop = true;
            

            angular.forEach($scope.dataFrm.allRows, function(v, i){ 
                
                var newIndx = parseInt(indx) - parseInt(1);
                var thirdObj = v.colArray[newIndx];

                if(newIndx >= 0) {
                    v.colArray[newIndx] = v.colArray[indx];
                    v.colArray[indx] = thirdObj;  
                }

                thirdObj = {};
     
            });

            $scope.posLoaderTop = false;
        }

 
    
    };
    
    $scope.goRight = function(indx, period = 'CALENDAR_YR') {

        if(period == 'CUMULATIVE') {
            $scope.posLoaderTopMonthly = true;
            angular.forEach($scope.dataFrm.allRowsMonthly, function(v, i){ 
                
                var newIndx = parseInt(indx) + parseInt(1);
                var thirdObj = v.colArray[newIndx];
                
                if(newIndx < v.colArray.length) {
                    v.colArray[newIndx] = v.colArray[indx];
                    v.colArray[indx] = thirdObj;  
                }

                thirdObj = {};
     
            });
         
            $scope.posLoaderTopMonthly = false;
        } else {
            $scope.posLoaderTop = true;
            angular.forEach($scope.dataFrm.allRows, function(v, i){ 
                
                var newIndx = parseInt(indx) + parseInt(1);
                var thirdObj = v.colArray[newIndx];
                
                if(newIndx < v.colArray.length) {
                    v.colArray[newIndx] = v.colArray[indx];
                    v.colArray[indx] = thirdObj;  
                }

                thirdObj = {};
     
            });
         
            $scope.posLoaderTop = false;
        
        }
    };


       
      var objList = {};
      objList.call = 'fund';
      objList.stype = 'listPerformance';
      objList.fund_id = $routeParams.id;
 
      var promise_list = $http({method: 'POST', url: 'cms/fund/performance', data: objList});

       promise_list.then(
          function(payload) {
                //console.log(payload);

                $scope.dataFrm.allRows = payload.data.calendar_yr_performance_table || new Array();
                $scope.dataFrm.allRowsMonthly = payload.data.cumulative_performance_table || new Array();
                // console.log($scope.dataFrm.allRows);
          },
          function(errorPayload) {
              console.log('failure loading', errorPayload);
          }
      );



    $q.all([promise, promise_list]).then(function(result){

        $scope.fullPageLoader = 0;
        $scope.submitProcess = 0;

        if(parseInt($scope.dataFrm.allRows.length) == parseInt(0)) {
            $scope.addMoreRows();
        }

        if(parseInt($scope.dataFrm.allRowsMonthly.length) == parseInt(0)) {
            $scope.addMoreRows('CUMULATIVE');
        }


         // if(parseInt($scope.dataFrm.allRows.length) == parseInt(0)) {
         //    $scope.dataFrm.allRows.push({id:'', rtype: 'CUMULATIVE_RETURNS', label_name: 'Fund NAV', one_month: '', three_month: '', ytd: '', one_year: '', three_year: '', since_inception: ''});
         //    $scope.dataFrm.allRows.push({id:'', rtype: 'CUMULATIVE_RETURNS', label_name: 'Closing Price', one_month: '', three_month: '', ytd: '', one_year: '', three_year: '', since_inception: ''});    
         // }
         
  
        
 
    });
    

    $scope.activateDeactivatePerformance = function() { 

        $http({method: 'POST', url: 'cms/fund/performance/activate', data: {call: 'fund', stype:'activateDeactivatePerformance', fund_id: $routeParams.id, f_active_performance: $scope.dataFrm.f_active_performance}}).success(function(response){
              //console.log(response);
              swal({
                title:'Success',
                icon:'success',
                timer:3000
              });
        });
    };
    

    $scope.submit = function() {
        $scope.submitProcess = 1;
        $scope.dataFrm.call = 'fund';
        $scope.dataFrm.stype = 'savePerformance';

        $http({method: 'POST', url: 'cms/fund/performance/save', data: $scope.dataFrm}).success(function(response){
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
                      //$location.path("/listIndex/1");
                      //$scope.dataFrm = {};
                  }
              }, 3000);

          });


    };
    
  

}]);
