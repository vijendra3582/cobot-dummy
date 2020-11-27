angular.module('truemarkApp').controller('addFundDataAndPricingController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'FundDetail', 'Upload', '$filter', '$q', function($http, myConfig, $location, $routeParams, $scope, $timeout, FundDetail , Upload, $filter, $q){
    $scope.dataFrm = {};
    $scope.dataFrm.allRows = [];
    $scope.fullPageLoader = 0;
    $scope.toTimestamp = function(date) {
      return new Date(date).getTime();
    };
     
    $scope.fullPageLoader = 1;
     
    var promise = FundDetail.getFundDetail($routeParams.id);
    
    var objList = {};
    objList.call = 'fund';
    objList.stype = 'getFundDataAndPricing';
    objList.fund_id = $routeParams.id;
     
    var promise_list = $http({method: 'POST', url: 'cms/fund/data-and-pricing', data: objList});
      
    $q.all([promise, promise_list]).then(function(result){
        
        console.log(result);
        
        $scope.fullPageLoader = 0;
        $scope.submitProcess = 0;
     
        var objFund = result[0].data.data; 
        $scope.dataFrm.fund_id = objFund.id;
        $scope.dataFrm.fund_name = objFund.fund_name ? objFund.fund_name.replace(/\\/g,'') : '';
        $scope.dataFrm.fund_ticker = objFund.fund_ticker ? objFund.fund_ticker.replace(/\\/g,'') : '';  
        $scope.dataFrm.is_outcome_product = objFund.is_outcome_product;
        
        if(objFund.fund_data_and_pricing_as_of_date != null && objFund.fund_data_and_pricing_as_of_date != "0000-00-00") {
            $scope.dataFrm.fund_data_and_pricing_as_of_date = $filter('date')(objFund.fund_data_and_pricing_as_of_date, "MM-dd-yyyy"); // for conversion to string
            // alert($scope.dataFrm.fund_data_and_pricing_as_of_date);
        } else {
            $scope.dataFrm.fund_data_and_pricing_as_of_date = '';
        }
        
        //menu title ======================
        //$scope.global_f_title_asset_allocation = objFund.f_title_asset_allocation.replace(/\\/g,'');
        
        
        var objData = result[1].data.data;
        
        if(parseInt(objData.length) > parseInt(0)) {
            angular.forEach(objData, function(value, key) {
               
               if(parseInt(value.status) == parseInt(1)) {
                    value.status = true;
               } else {
                    value.status = false;
               }
               
               if(parseInt(value.display_status) == parseInt(1)) {
                    value.display_status = true;
               } else {
                    value.display_status = false;
               }
               
               if(parseInt(value.do_not_update) == parseInt(1)) {
                    value.do_not_update = true;
               } else {
                    value.do_not_update = false;
               }
               
               value.data_head = value.data_head ? value.data_head.replace(/\\/g, '') : '';
               value.data_value = value.data_value ? value.data_value.replace(/\\/g, '') : '';
                              
               $scope.dataFrm.allRows.push(value);  
            });
              
        } else {
        
            $scope.dataFrm.allRows.push({id:'', data_type: 'NET_ASSETS', data_head: 'Net Assets', data_value: '', display_status: 1, tags: 'DIFF_TABLE', tags_field: 'net_assets', tags_table: 'daily_nav_usbanks', tags_cond: '1', do_not_update: 1, status: 1});
            $scope.dataFrm.allRows.push({id:'', data_type: 'NAV', data_head: 'NAV', data_value: '', display_status: 1, tags: 'DIFF_TABLE', tags_field: 'nav', tags_table: 'daily_nav_usbanks', tags_cond: '1', do_not_update: 1, status: 1});
            $scope.dataFrm.allRows.push({id:'', data_type: 'SHARES_OUTSTANDING', data_head: 'Shares Outstanding', data_value: '', display_status: 1, tags: 'DIFF_TABLE', tags_field: 'shares_outstanding', tags_table: 'daily_nav_usbanks', tags_cond: '1', do_not_update: 1, status: 1});
            $scope.dataFrm.allRows.push({id:'', data_type: 'PREMIUM_DISCOUNT_PERCENTAGE', data_head: 'Premium/discount Percentage', data_value: '', display_status: 1, tags: 'DIFF_TABLE', tags_field: 'premium_discount', tags_table: 'daily_nav_usbanks', tags_cond: '1', do_not_update: 1, status: 1});
            $scope.dataFrm.allRows.push({id:'', data_type: 'CLOSING_PRICE', data_head: 'Closing Price', data_value: '', display_status: 1, tags: 'DIFF_TABLE', tags_field: 'market_price', tags_table: 'daily_nav_usbanks', tags_cond: '1', do_not_update: 1, status: 1});
            
			//Nawal $scope.dataFrm.allRows.push({id:'', data_type: 'MEDIAN_30_DAY_SPREAD_PERCENTAGE', data_head: '30 Day Median Bid/Ask Spread', data_value: '', display_status: 1, tags: 'DIFF_TABLE', tags_field: 'median_30_day_spread_percentage', tags_table: 'daily_nav_usbanks', tags_cond: '1', do_not_update: 1, status: 1});
        }
        
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
                    index_position: key
                });
        
            });
        
            /*
            $http({method: 'POST', url: myConfig.ajax_url, data: {call: 'index', stype:'savePosition', data: secPosArray}}).success(function(response){
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
                    index_id: objVal.index_id,
                    index_position: key
                });
        
            });
        
            /*
            $http({method: 'POST', url: myConfig.ajax_url, data: {call: 'index', stype:'savePosition', data: secPosArray}}).success(function(response){
                //console.log(response);
                $scope.posLoader = false;
            });
            */
        
            $scope.posLoader = false;
        
        };
        
        
        $scope.submit = function() {
            $scope.submitProcess = 1;
            $scope.dataFrm.call = 'fund';
            $scope.dataFrm.stype = 'saveFundDataAndPricing';
            
        
            $http({method: 'POST', url: 'cms/fund/data-and-pricing/save', data: $scope.dataFrm}).success(function(response){
                  $scope.submitProcess = 2;
        
                  if(response.SUCCESS == '1')
                  {
                      $scope.submitProcessMsg = '&#x2714; Successfully Saved';
                      swal({
                        title:"Success",
                        text:"successfully saved",
                        icon:"success",
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
                          location.href = "#addFundDataAndPricing/" + $routeParams.id + "?A=" + Math.random();
                          //$scope.dataFrm = {};
                      }
                  }, 3000);
        
              });
        
        
        }; 
        
        $scope.addMore = function() {
            $scope.dataFrm.allRows.push({id:'', data_type: '', data_head: '', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1});
        };
        
    });

    $scope.deleteFile = function(idx) {    
      if($scope.dataFrm.allRows[idx].id) {
        $scope.fullPageLoader = 1;
          var id = $scope.dataFrm.allRows[idx].id;
          var c = confirm("Are you sure you wish to delete this?");
          if(c) {
              
              $scope.dataFrm.allRows[idx].loader = true;
              // alert(idx + "===" + id); 
              $http({method: 'POST', url: 'cms/fund/data-and-pricing/delete', data: {call: 'fund', stype:'deleteFundDetail', fund_id: $routeParams.id, id: id}}).success(function(response){
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
      $scope.fullPageLoader = 0;
    };
     
    $scope.editorConfig = {
	    sanitize: false,
	    toolbar: [{ name: 'basicStyling', items: ['bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript']}]
	};
}]);