angular.module('truemarkApp').controller('subscribedbController', ['$scope', '$http', 'subscribedbDetail', '$httpParamSerializer', '$location', '$routeParams', 'myConfig', '$timeout', '$filter', function($scope, $http, subscribedbDetail, $httpParamSerializer, $location, $routeParams, myConfig, $timeout, $filter){
    
    $scope.allArray = {};
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
        objData.search_name = $location.search().search_name || "";
        objData.search_email = $location.search().search_email || "";
        objData.m = Math.random();
        $location.url("listSubscribe/" + newPage + "/").search(objData);
    };

     var dataToSend = {};
      dataToSend.call = 'subscribecontent';
      dataToSend.stype = 'getData';
      dataToSend.perpage = $scope.itemsPerPage;
      dataToSend.search_name = $location.search().search_name || "";
      dataToSend.search_email = $location.search().search_email || "";
    var promise = $http({method: 'POST', url: 'cms/subscribedb/list/'+$routeParams.page, data: dataToSend});
    promise.then(
        function(payload) { 
            $scope.allArray = payload.data.data;
            $scope.searchedFields = payload.data.searchedFields;
            $scope.totalItems = payload.data.total_records;
            $scope.listPageLoader = 0;
            $scope.searchFrm.search_name = payload.data.searchedFields.search_name;
            $scope.searchFrm.search_email = payload.data.searchedFields.search_email;

            //console.log($scope.allArray); 
            $scope.showCheckAll = true;


        },
        function(errorPayload) {
            console.log('failure loading', errorPayload);
            $scope.listPageLoader = 0;
        }
    );
    

    /******* export excel********/
    $scope.exportExcel = function(){ 
        // var c = confirm("Are you sure you wish to export ?");
        // if(c)
        // {

            var search_name = $location.search().search_name || "";
            var search_email = $location.search().search_email || "";

            $scope.fullPageLoader = 1;
            $http({method: 'POST', url:'cms/export-subscribe' , data: {call: 'subscribecontent', stype:'exportExcel', 'DIDS': '','search_name':search_name, 'search_email':search_email}}).success(function(response){
                 // console.log(response);
                $timeout(function(){
                    $scope.fullPageLoader = 0;
                    if(response.SUCCESS == '1')
                      {
                        window.location.href = response.DATA;
                        $scope.pageChanged(1);
                      }
                    
                }, 2000);

            });
        // }
    }

    $scope.clearSearch = function() {
        $scope.searchFrm = {};
        $scope.searchFrm.m = Math.random();
        $location.url("/listSubscribe/1/").search($scope.searchFrm);

    };

    $scope.search = function() {
        $scope.searchFrm.m = Math.random(); 
        $location.url("/listSubscribe/1/").search($scope.searchFrm);
    };

    ///============ FOR CHECK ALL CHECKBOX ================
    $scope.hId = {
        roles: []
    };

    $scope.checkAll = function(chk) {
        if(chk == "YES") {
            $scope.hId.roles = angular.copy($scope.allArray);
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
                $http({method: 'POST', url:'cms/subscribedb/deleteAll' , data: {call: 'subscribecontent', stype:'deleteAllData', 'DIDS': $scope.hId.roles}}).success(function(response){
                     
                    $scope.successMsg = response.MSG;

                    $timeout(function(){
                        $scope.fullPageLoader = 0;
                        if(response.SUCCESS == '1')
                        {
                          swal({
                            title: "Success", 
                            icon: "success",
                            timer: 3000
                            //button: "Ok",
                          });
                            $scope.pageChanged(1);
                            //$location.path('/listProductCategory');
                        }
                    }, 2000);

                });
            }
        }
        else
        {
            alert("Please select a row");
        }
    };


    $scope.deleteData = function(id) {
        var c = confirm("Are you sure you wish to delete?");
        if(c)
        {
            $scope.fullPageLoader = 1;
            $http({method: 'POST', url: 'cms/subscribedb/delete', data: {call: 'subscribecontent', stype:'deleteData', 'id': id}}).success(function(response){
                // console.log(response);
                $scope.successMsg = response.MSG;

                $timeout(function(){
                    $scope.fullPageLoader = 0;
                    if(response.SUCCESS == '1')
                    {
                        swal({
                          title: "Success",
                          text: response.MSG,
                          icon: "success",
                          timer: 3000
                          //button: "Ok",
                        });
                        $scope.pageChanged(1); 
                    }else{
                        swal({
                          title: "Error",
                          text: response.MSG,
                          icon: "error",
                          timer: 3000
                          //button: "Ok",
                        });
                    }
                }, 2000);
            });
        }
    };
   

}]);
