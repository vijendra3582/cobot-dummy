angular.module('truemarkApp').controller('addFundDetailsController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'FundDetail', 'Upload', '$filter', '$q', function($http, myConfig, $location, $routeParams, $scope, $timeout, FundDetail , Upload, $filter, $q){
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
    objList.stype = 'getFundDetails';
    objList.fund_id = $routeParams.id;
     
    var promise_list = $http({method: 'POST', url: 'cms/fund/details', data: objList});
      
    $q.all([promise, promise_list]).then(function(result){
        
        //console.log(result);
        
        $scope.fullPageLoader = 0;
        $scope.submitProcess = 0;
     
        var objFund = result[0].data.data; 
        $scope.dataFrm.fund_id = objFund.id;
        $scope.dataFrm.fund_name = objFund.fund_name ? objFund.fund_name.replace(/\\/g,'') : '';
        $scope.dataFrm.fund_ticker = objFund.fund_ticker ? objFund.fund_ticker.replace(/\\/g,'') : '';  
        $scope.dataFrm.is_outcome_product = objFund.is_outcome_product; 
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
        
            $scope.dataFrm.allRows.push({id:'', data_type: 'FUND_INCEPTION', data_head: 'Fund Inception', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1}); 
            $scope.dataFrm.allRows.push({id:'', data_type: 'TICKER', data_head: 'Ticker', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1});
            $scope.dataFrm.allRows.push({id:'', data_type: 'PRIMARY_EXCHANGE', data_head: 'Primary Exchange', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1});
            $scope.dataFrm.allRows.push({id:'', data_type: 'CUSIP', data_head: 'CUSIP', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1});
             
            $scope.dataFrm.allRows.push({id:'', data_type: 'IOPV_SYMBOL', data_head: 'IOPV Symbol', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1});
            $scope.dataFrm.allRows.push({id:'', data_type: 'NAV_SYMBOL', data_head: 'NAV Symbol', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1});
            $scope.dataFrm.allRows.push({id:'', data_type: 'EXPENSE_RATIO', data_head: 'Expense Ratio', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1});
            $scope.dataFrm.allRows.push({id:'', data_type: '30_DAY_SEC_YIELD', data_head: '30 Day SEC Yield *', data_value: '', display_status: 1, tags: '', tags_field: '', tags_table: '', tags_cond: '', do_not_update: 1, status: 1});
        
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
             
        
            $scope.posLoader = false;
        
        
        
        };
        
        $scope.goDown = function(indx) {
            $scope.posLoader = true;
            var newIndx = parseInt(indx) + parseInt(1);
            var thirdObj = $scope.dataFrm.allRows[newIndx];
             
            $scope.dataFrm.allRows[newIndx] = $scope.dataFrm.allRows[indx];
            $scope.dataFrm.allRows[indx] = thirdObj;
        
            thirdObj = {};
         
            $scope.posLoader = false;
        
        };
        
        
        $scope.submit = function() {
            $scope.submitProcess = 1;
            $scope.dataFrm.call = 'fund';
            $scope.dataFrm.stype = 'saveFundDetails';
            
        
            $http({method: 'POST', url: 'cms/fund/details/save', data: $scope.dataFrm}).success(function(response){
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
                          location.href = "#addFundDetails/" + $routeParams.id + "?A=" + Math.random();
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
                $http({method: 'POST', url: 'cms/fund/details/delete', data: {call: 'fund', stype:'deleteFundDetail', fund_id: $routeParams.id, id: id}}).success(function(response){
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