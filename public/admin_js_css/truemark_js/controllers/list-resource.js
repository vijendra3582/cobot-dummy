    angular.module('truemarkApp').controller('listResourceController', ['$scope', '$http', 'ResourceDetail', '$httpParamSerializer', '$location', '$routeParams', 'myConfig', '$timeout', '$filter', function($scope, $http, ResourceDetail, $httpParamSerializer, $location, $routeParams, myConfig, $timeout, $filter){
        $scope.allResourceArray = {};
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
            objData.search_title = $location.search().search_title || ""; 
            objData.search_status = $location.search().search_status || "";
            objData.m = Math.random();
            console.log(objData);
            $location.url("/listResources/" + newPage + "/").search(objData);
        };

        var dataToSend = {};
          dataToSend.call = 'resource';
          dataToSend.stype = 'getData';
          dataToSend.perpage = $scope.itemsPerPage;
          dataToSend.search_title = $location.search().search_title || "";
          dataToSend.search_status = $location.search().search_status || "";
        var promise = $http({method: 'POST', url: 'cms/resource/list/'+$routeParams.page, data: dataToSend});
        promise.then(
            function(payload) {
                console.log(payload);
                $scope.allResourceArray = payload.data.data;
                // $scope.searchedFields = payload.data.searchedFields;
                $scope.searchFrm = payload.data.searchedFields;

                $scope.totalItems = payload.data.total_records;
                $scope.listPageLoader = 0;

                console.log($scope.allResourceArray);
                
                $scope.showCheckAll = true;


            },
            function(errorPayload) {
                console.log('failure loading news', errorPayload);
                $scope.listPageLoader = 0;
            }
        );

        $scope.clearSearch = function() {
            $scope.searchFrm = {};
            $scope.searchFrm.m = Math.random();
            $location.url("/listResources/1/").search($scope.searchFrm);

        };

        $scope.search = function() {
            $scope.searchFrm.m = Math.random(); 
            $location.url("/listResources/1/").search($scope.searchFrm);
        };


        ///============ FOR CHECK ALL CHECKBOX ================
        $scope.hId = {
            roles: []
        };

        $scope.checkAll = function(chk) {
            if(chk == "YES") {
                $scope.hId.roles = angular.copy($scope.allResourceArray);
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
                    $http({method: 'POST', url: 'cms/resource/deleteAll', data: {call: 'resource', stype:'deleteAllData', 'DIDS': $scope.hId.roles}}).success(function(response){
                        //console.log(response);
                        $scope.successMsg = response.MSG;

                        $timeout(function(){
                            $scope.fullPageLoader = 0;
                            if(response.SUCCESS == '1')
                            {
                                swal({
                                  title: "Success", 
                                  icon: "success",
                                  timer: 3000 
                                });
                                $scope.pageChanged(1);
                            }
                        }, 2000);

                    });
                }
            }
            else
            {
                alert("Please select a resource");
            }


        };


        $scope.deleteData = function(did) {
            var c = confirm("Are you sure you wish to delete?");
            if(c)
            {
                $scope.fullPageLoader = 1;
                $http({method: 'POST', url: 'cms/resource/delete', data: {call: 'resource', stype:'deleteData', 'resource_id': did}}).success(function(response){
                    $scope.successMsg = response.MSG;

                    $timeout(function(){
                        $scope.fullPageLoader = 0;
                        if(response.SUCCESS == '1')
                        {
                            swal({
                                  title: "Success", 
                                  icon: "success",
                                  timer: 3000 
                                });
                            $scope.pageChanged(1);
                        }
                    }, 2000);
                });
            }
        };

        $scope.updateStatus = function(resource_id, resource_current_status, $index) {
            //alert(news_id + "===" + news_current_status)
            if(parseInt(resource_current_status) == parseInt(1))
            {
                resource_now_status = 0;
            }
            else
            {
                resource_now_status = 1;
            }

            $scope.allResourceArray[$index].loading = true;


            //// update ajax here ========
            $http({method: 'POST', url: 'cms/resource/updateStatus', data: {call: 'resource', stype:'updateStatus', resource_id: resource_id, resource_status: resource_now_status}}).success(function(response){
                //console.log(response);
                // swal({
                //   title: "Success", 
                //   icon: "success",
                //   timer: 3000 
                // });
                $scope.allResourceArray[$index].loading = false;
                $scope.allResourceArray[$index].status = resource_now_status;


            });


            //console.log($scope.allNewswArray);

        };

        /////////// POSITION =====================
        $scope.posLoader = false;
        $scope.goUp = function(indx) {
            $scope.posLoader = true;
            var newIndx = parseInt(indx) - parseInt(1);
            //alert(indx + "==" + catId + "==" + newIndx);
            var thirdObj = $scope.allResourceArray[newIndx];

            $scope.allResourceArray[newIndx] = $scope.allResourceArray[indx];
            $scope.allResourceArray[indx] = thirdObj;

            thirdObj = {};

            var secPosArray = [];
            angular.forEach($scope.allResourceArray, function(objVal, key) {
                //this.push(key + ': ' + value);
                //console.log(key + ': ' + objVal.section_id);
                secPosArray.push({
                    id: objVal.id,
                    position: key 
                });

            });


            $http({method: 'POST', url:'cms/resource/updatePosition', data: {call: 'resource', stype:'savePosition', data: secPosArray}}).success(function(response){
                //console.log(response);
                $scope.posLoader = false;
            });
        };

        $scope.goDown = function(indx) {
            $scope.posLoader = true;
            var newIndx = parseInt(indx) + parseInt(1);
            //alert(indx + "==" + catId + "==" + newIndx);
            var thirdObj = $scope.allResourceArray[newIndx];

            $scope.allResourceArray[newIndx] = $scope.allResourceArray[indx];
            $scope.allResourceArray[indx] = thirdObj;

            thirdObj = {};

            //console.log($scope.allSectionArray);

            var secPosArray = [];
            angular.forEach($scope.allResourceArray, function(objVal, key) {
                //this.push(key + ': ' + value);
                //console.log(key + ': ' + objVal.section_id);
                secPosArray.push({
                    id: objVal.id,
                    position: key 
                });

            });


            $http({method: 'POST', url: 'cms/resource/updatePosition', data: {call: 'resource', stype:'savePosition', data: secPosArray}}).success(function(response){
                //console.log(response);
                $scope.posLoader = false;
            });

        };

    }]);
