    angular.module('truemarkApp').controller('listHomeNewsController', ['$scope', '$http', 'NewsDetail', '$httpParamSerializer', '$location', '$routeParams', 'myConfig', '$timeout', '$filter', function($scope, $http, NewsDetail, $httpParamSerializer, $location, $routeParams, myConfig, $timeout, $filter){
        $scope.allNewsArray = {};
        $scope.searchedFields = {};
        $scope.fullPageLoader = 0;
        $scope.listPageLoader = 1;

        $scope.searchFrm = {};

        $scope.totalItems = 0;
        $scope.itemsPerPage = '100'; // this should match however many results your API puts on one page

        $scope.pagination = {
            current: $routeParams.page
        };

        $scope.pageChanged = function(newPage) {
            var objData = {};
            objData.m = Math.random();
            console.log(objData);
            $location.url("/listNews/" + newPage + "/").search(objData);
        };

        var dataToSend = {};
          dataToSend.call = 'news';
          dataToSend.stype = 'getData';
          //dataToSend.perpage = $scope.itemsPerPage;
         
        var promise = $http({method: 'POST', url: 'cms/news-position/list', data: dataToSend});
        promise.then(
            function(payload) {
                console.log(payload);
                $scope.allNewsArray = payload.data.data;
                $scope.totalItems = payload.data.total_records;
                $scope.listPageLoader = 0;

                console.log($scope.allNewsArray);
                /*
                var found = $filter('filter')(payload.data.data, function (d) {return d.count_booked > 0;})[0];
                if(found) {
                    $scope.showCheckAll = false;
                } else {
                    $scope.showCheckAll = true;
                }
                */
                $scope.showCheckAll = true;


            },
            function(errorPayload) {
                console.log('failure loading news', errorPayload);
                $scope.listPageLoader = 0;
            }
        );

        
         /********************** Positions ******************************/
            /////////// POSITION =====================
    $scope.posLoader = false;
    $scope.goUp = function(indx) {
        $scope.posLoader = true;
        var newIndx = parseInt(indx) - parseInt(1);
        //alert(indx + "==" + catId + "==" + newIndx);
        var thirdObj = $scope.allNewsArray[newIndx];

        $scope.allNewsArray[newIndx] = $scope.allNewsArray[indx];
        $scope.allNewsArray[indx] = thirdObj;

        thirdObj = {};

        var secPosArray = [];
        angular.forEach($scope.allNewsArray, function(objVal, key) { 
            secPosArray.push({
                id: objVal.id,
                position: key 
            });

        }); 
        $http({method: 'POST', url: 'cms/news/position', data: {call: 'news', stype:'savePosition', data: secPosArray}}).success(function(response){
            //console.log(response);
            $scope.posLoader = false;
        }); 
    };

    $scope.goDown = function(indx) {
        $scope.posLoader = true;
        var newIndx = parseInt(indx) + parseInt(1);
        //alert(indx + "==" + catId + "==" + newIndx);
        var thirdObj = $scope.allNewsArray[newIndx];

        $scope.allNewsArray[newIndx] = $scope.allNewsArray[indx];
        $scope.allNewsArray[indx] = thirdObj;

        thirdObj = {};

        //console.log($scope.allSectionArray);

        var secPosArray = [];
        angular.forEach($scope.allNewsArray, function(objVal, key) {
            //this.push(key + ': ' + value);
            //console.log(key + ': ' + objVal.section_id);
            secPosArray.push({
                id: objVal.id,
                position: key 
            });

        });


        $http({method: 'POST', url: 'cms/news/position', data: {call: 'news', stype:'savePosition', data: secPosArray}}).success(function(response){
            //console.log(response);
            $scope.posLoader = false;
        });

    }; 

    }]);
