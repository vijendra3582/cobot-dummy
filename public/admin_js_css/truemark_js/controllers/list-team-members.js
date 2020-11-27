    angular.module('truemarkApp').controller('listTeamMemberController', ['$scope', '$http', 'TeamMemberDetail', '$httpParamSerializer', '$location', '$routeParams', 'myConfig', '$timeout', '$filter', function($scope, $http, TeamMemberDetail, $httpParamSerializer, $location, $routeParams, myConfig, $timeout, $filter){
        
        $scope.allTeamArray = {};
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
            objData.search_team_name = $location.search().search_team_name || "";
            objData.search_team_status = $location.search().search_team_status || "";
            objData.m = Math.random();
            $location.url("/listTeam/" + newPage + "/").search(objData);
        };

         var dataToSend = {};
          dataToSend.call = 'listTeam';
          dataToSend.stype = 'getData';
          dataToSend.perpage = $scope.itemsPerPage;
          dataToSend.search_title = $location.search().search_title || "";
          dataToSend.search_status = $location.search().search_status || "";
        var promise = $http({method: 'POST', url: 'cms/team/list/'+$routeParams.page, data: dataToSend});
        
        promise.then(
            function(payload) {
                //console.log(payload);
                $scope.allTeamArray = payload.data.data;
                $scope.searchedFields = payload.data.searchedFields;
                $scope.totalItems = payload.data.total_records;
                $scope.listPageLoader = 0;

                console.log($scope.allTeamArray);
                
                $scope.showCheckAll = true;


            },
            function(errorPayload) {
                console.log('failure loading team', errorPayload);
                $scope.listPageLoader = 0;
            }
        );

        $scope.clearSearch = function() {
            $scope.searchFrm = {};
            $scope.searchFrm.m = Math.random();
            $location.url("/listTeam/1/").search($scope.searchFrm);

        };

        $scope.search = function() {
            $scope.searchFrm.m = Math.random();
            $location.url("/listTeam/1/").search($scope.searchFrm);
        };


        ///============ FOR CHECK ALL CHECKBOX ================
        $scope.hId = {
            roles: []
        };

        $scope.checkAll = function(chk) {
            if(chk == "YES") {
                $scope.hId.roles = angular.copy($scope.allTeamArray);
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
                    $http({method: 'POST', url: 'cms/team/deleteAll', data: {call: 'team', stype:'deleteAllData', 'DIDS': $scope.hId.roles}}).success(function(response){
                        //console.log(response);
                        $scope.successMsg = response.MSG;

                        $timeout(function(){
                            $scope.fullPageLoader = 0;
                            if(response.SUCCESS == '1')
                            {
                                $scope.pageChanged(1);
                            }
                        }, 2000);

                    });
                }
            }
            else
            {
                alert("Please select a team member");
            } 
        };


        $scope.deleteData = function(did) {
            var c = confirm("Are you sure you wish to delete?");
            if(c)
            {
                $scope.fullPageLoader = 1;
                $http({method: 'POST', url: 'cms/team/delete', data: {call: 'team', stype:'deleteData', 'team_id': did}}).success(function(response){
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
                        }
                    }, 2000);
                });
            }
        };

        $scope.updateStatus = function(team_id, team_current_status, $index) {
            //alert(team_id + "===" + team_current_status)
            if(parseInt(team_current_status) == parseInt(1))
            {
                team_now_status = 0;
            }
            else
            {
                team_now_status = 1;
            }

            $scope.allTeamArray[$index].loading = true;

            //// update ajax here ========
            $http({method: 'POST', url: 'cms/team/updateStatus', data: {call: 'team', stype:'updateStatus', team_id: team_id, team_status: team_now_status}}).success(function(response){
                //console.log(response);
                $scope.allTeamArray[$index].loading = false;
                $scope.allTeamArray[$index].status = team_now_status;
            }); 
            //console.log($scope.allTeamwArray); 
        };
        
         
        
        /////////// POSITION =====================
        $scope.posLoader = false;
        $scope.goUp = function(indx) {
            $scope.posLoader = true;
            var newIndx = parseInt(indx) - parseInt(1);
            //alert(indx + "==" + catId + "==" + newIndx);
            var thirdObj = $scope.allTeamArray[newIndx];

            $scope.allTeamArray[newIndx] = $scope.allTeamArray[indx];
            $scope.allTeamArray[indx] = thirdObj;

            thirdObj = {};

            var secPosArray = [];
            angular.forEach($scope.allTeamArray, function(objVal, key) {
                //this.push(key + ': ' + value);
                //console.log(key + ': ' + objVal.section_id);
                secPosArray.push({
                    team_id: objVal.id,
                    team_position: key 
                });

            });


            $http({method: 'POST', url:'cms/team/updatePosition', data: {call: 'team', stype:'savePosition', data: secPosArray}}).success(function(response){
                //console.log(response);
                $scope.posLoader = false;
            });
        };

        $scope.goDown = function(indx) {
            $scope.posLoader = true;
            var newIndx = parseInt(indx) + parseInt(1);
            //alert(indx + "==" + catId + "==" + newIndx);
            var thirdObj = $scope.allTeamArray[newIndx];

            $scope.allTeamArray[newIndx] = $scope.allTeamArray[indx];
            $scope.allTeamArray[indx] = thirdObj;

            thirdObj = {};

            //console.log($scope.allSectionArray);

            var secPosArray = [];
            angular.forEach($scope.allTeamArray, function(objVal, key) {
                //this.push(key + ': ' + value);
                //console.log(key + ': ' + objVal.section_id);
                secPosArray.push({
                    team_id: objVal.id,
                    team_position: key 
                });

            });


            $http({method: 'POST', url: 'cms/team/updatePosition', data: {call: 'team', stype:'savePosition', data: secPosArray}}).success(function(response){
                //console.log(response);
                $scope.posLoader = false;
            });

        };

    }]);
