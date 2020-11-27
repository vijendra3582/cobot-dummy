angular.module('truemarkApp').controller('listFundsController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'FundDetail', 'Upload', '$filter', function($http, myConfig, $location, $routeParams, $scope, $timeout, FundDetail , Upload, $filter){
    $scope.allFundArray = {};
    $scope.allStructuredArray = {};
    $scope.searchedFields = {};
    $scope.fullPageLoader = 0;
    $scope.listPageLoader = 1;

    $scope.searchFrm = {};

    $scope.totalItems = 0;
    $scope.itemsPerPage = '50'; // this should match however many results your API puts on one page

    $scope.pagination = {
        current: $routeParams.page
    };

    $scope.pageChanged = function(newPage) {
        var objData = {};
        // objData.search_fund_name = $location.search().search_fund_name || "";
        // objData.search_fund_status = $location.search().search_fund_status || "";
        objData.m = Math.random();
        $location.url("/listFunds/" + newPage + "/").search(objData);
    };

     var dataToSend = {};
          dataToSend.call = 'funds';
          dataToSend.stype = 'getData';
          dataToSend.perpage = $scope.itemsPerPage;
          // dataToSend.search_news_title = $location.search().search_news_title || "";
          // dataToSend.search_news_publication = $location.search().search_news_publication || "";
          // dataToSend.search_news_status = $location.search().search_news_status || "";
    var promise = $http({method: 'POST', url: 'cms/funds/list/'+$routeParams.page, data: dataToSend});
    promise.then(
        function(payload) {
            //console.log(payload);
            $scope.allFundArray = payload.data.data[0];
            $scope.allStructuredArray = payload.data.data[1];
            $scope.searchedFields = payload.data.searchedFields;
            $scope.totalItems = payload.data.total_records;
            $scope.listPageLoader = 0;

            //console.log($scope.allFundArray);
            /*
            var found = $filter('filter')(payload.data.data, function (d) {return d.count_booked > 0;})[0];
            if(found) {
                $scope.showCheckAll = false;
            } else {
                $scope.showCheckAll = true;
            }
            */
            $scope.showCheckAll = true;
            $scope.showCheckAllStructured = true;


        },
        function(errorPayload) {
            console.log('failure loading fund', errorPayload);
            $scope.listPageLoader = 0;
        }
    );

    $scope.clearSearch = function() {
        $scope.searchFrm = {};
        $scope.searchFrm.m = Math.random();
        $location.url("/listFund/1/").search($scope.searchFrm);

    };

    $scope.search = function() {
        $scope.searchFrm.m = Math.random();
        $location.url("/listFund/1/").search($scope.searchFrm);
    };


    ///============ FOR CHECK ALL CHECKBOX ================
    $scope.hId = {
        roles: []
    };

    $scope.checkAll = function(chk) {
        if(chk == "YES") {
            $scope.hId.roles = angular.copy($scope.allFundArray);
        } else {
            $scope.hId.roles = [];
        }
    };

    $scope.checkAllStructured = function(chk) {
        if(chk == "YES") {
            $scope.hId.roles = angular.copy($scope.allStructuredArray);
        } else {
            $scope.hId.roles = [];
        }
    };

    $scope.successMsg = '';
    ///////============ DELETE ALL ================================
    $scope.deleteAll = function() {

        if($scope.hId.roles.length > 0)
        {
            var c = confirm("Are you sure you wish to delete?");
            if(c)
            {
                $scope.fullPageLoader = 1;
                $http({method: 'POST', url: 'cms/fund/fundDeleteAll', data: {call: 'fund', stype:'deleteAllData', 'DIDS': $scope.hId.roles}}).success(function(response){
                    //console.log(response);
                    $scope.successMsg = response.MSG;

                    $timeout(function(){
                        $scope.fullPageLoader = 0;
                        if(response.SUCCESS == '1')
                        {    
                            swal({
                                'title':'Success',
                                'icon':'success',
                                'timer':2000
                            });
                            $scope.pageChanged(1);
                        }
                    }, 2000);

                });
            }
        }
        else
        {
            alert("Please select an fund");
        }


    };


    $scope.deleteData = function(did) {
        var c = confirm("Are you sure you wish to delete?");
        if(c)
        {
            $scope.fullPageLoader = 1;
            $http({method: 'POST', url: 'cms/fund/delete', data: {call: 'fund', stype:'deleteData', 'fund_id': did}}).success(function(response){
                $scope.successMsg = response.MSG;

                $timeout(function(){
                    $scope.fullPageLoader = 0;
                    if(response.SUCCESS == '1')
                    {    
                        swal({
                            'title':'Success',
                            'icon':'success',
                            'timer':3000
                        });
                        $scope.pageChanged(1);
                    }
                }, 2000);
            });
        }
    };

    $scope.updateStatus = function(fund_id, fund_current_status,array_type, $fund) {
        //alert(fund_id + "===" + fund_current_status)
        if(parseInt(fund_current_status) == parseInt(1))
        {
            fund_now_status = 0;
        }
        else
        {
            fund_now_status = 1;
        }

            
        if(array_type == 'structured'){
            $scope.allStructuredArray[$fund].loading = true;
        }
        else{
            $scope.allFundArray[$fund].loading = true;
        }
            



        //// update ajax here ========
        $http({method: 'POST', url: 'cms/fund/fundStatus', data: {call: 'fund', stype:'updateStatus', fund_id: fund_id, fund_status: fund_now_status}}).success(function(response){
            //console.log(response);

            if(array_type == 'structured'){
                $scope.allStructuredArray[$fund].loading = false;
                $scope.allStructuredArray[$fund].status = fund_now_status;
            }
            else{
                $scope.allFundArray[$fund].status = fund_now_status;
                $scope.allFundArray[$fund].loading = false;
            }


        });


        //console.log($scope.allFundArray);

    };
    
    
    /////////// POSITION =====================
    $scope.posLoader = false;
     
    $scope.goUp = function(indx,id,type) {
        if(type == 'structured'){
            $tempArr = $scope.allStructuredArray;
        }else{
            $tempArr = $scope.allFundArray;
        }
 
        $scope.posLoader = true;
        var newIndx = parseInt(indx) - parseInt(1);
        //alert(indx + "==" + catId + "==" + newIndx);
        var thirdObj = $tempArr[newIndx];

        $tempArr[newIndx] = $tempArr[indx];
        $tempArr[indx] = thirdObj;

        thirdObj = {};

        var secPosArray = [];
        angular.forEach($tempArr, function(objVal, key) {
            //this.push(key + ': ' + value);
            //console.log(key + ': ' + objVal.section_id);
            secPosArray.push({
                fund_id: objVal.id,
                fund_position: key 
            });

        });


        $http({method: 'POST', url: 'cms/fund/position', data: {call: 'fund', stype:'savePosition', data: secPosArray}}).success(function(response){
            //console.log(response);
            $scope.posLoader = false;
        });


        //console.log(secPosArray);


        //console.log($scope.allSectionArray);

    };

    $scope.goDown = function(indx,id,type) {
        if(type == 'structured'){
            $tempArr = $scope.allStructuredArray;
        }else{
            $tempArr = $scope.allFundArray;
        }
        $scope.posLoader = true;
        var newIndx = parseInt(indx) + parseInt(1);
        //alert(indx + "==" + catId + "==" + newIndx);
        var thirdObj = $tempArr[newIndx];

        $tempArr[newIndx] = $tempArr[indx];
        $tempArr[indx] = thirdObj;

        thirdObj = {};

        //console.log($scope.allSectionArray);

        var secPosArray = [];
        angular.forEach($tempArr, function(objVal, key) {
            //this.push(key + ': ' + value);
            //console.log(key + ': ' + objVal.section_id);
            secPosArray.push({
                fund_id: objVal.id,
                fund_position: key 
            });

        });


        $http({method: 'POST', url: 'cms/fund/position', data: {call: 'fund', stype:'savePosition', data: secPosArray}}).success(function(response){
            //console.log(response);
            $scope.posLoader = false;
        });

    };
}]);