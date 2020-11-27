    angular.module('truemarkApp').controller('listNewsController', ['$scope', '$http', 'NewsDetail', '$httpParamSerializer', '$location', '$routeParams', 'myConfig', '$timeout', '$filter', function($scope, $http, NewsDetail, $httpParamSerializer, $location, $routeParams, myConfig, $timeout, $filter){
        $scope.allNewsArray = {};
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
            objData.search_news_title = $location.search().search_news_title || "";
            objData.search_news_publication = $location.search().search_news_publication || "";
            objData.search_news_status = $location.search().search_news_status || "";
            objData.m = Math.random();
            console.log(objData);
            $location.url("/listNews/" + newPage + "/").search(objData);
        };

        var dataToSend = {};
          dataToSend.call = 'news';
          dataToSend.stype = 'getData';
          dataToSend.perpage = $scope.itemsPerPage;
          dataToSend.search_news_title = $location.search().search_news_title || "";
          dataToSend.search_news_publication = $location.search().search_news_publication || "";
          dataToSend.search_news_status = $location.search().search_news_status || "";
        var promise = $http({method: 'POST', url: 'cms/news/list/'+$routeParams.page, data: dataToSend});
        promise.then(
            function(payload) {
                console.log(payload);
                $scope.allNewsArray = payload.data.data;
                // $scope.searchedFields = payload.data.searchedFields;
                $scope.searchFrm = payload.data.searchedFields;

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

        $scope.clearSearch = function() {
            $scope.searchFrm = {};
            $scope.searchFrm.m = Math.random();
            $location.url("/listNews/1/").search($scope.searchFrm);

        };

        $scope.search = function() {
            $scope.searchFrm.m = Math.random(); 
            $location.url("/listNews/1/").search($scope.searchFrm);
        };


        ///============ FOR CHECK ALL CHECKBOX ================
        $scope.hId = {
            roles: []
        };

        $scope.checkAll = function(chk) {
            if(chk == "YES") {
                $scope.hId.roles = angular.copy($scope.allNewsArray);
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
                    $http({method: 'POST', url: 'cms/news/deleteAll', data: {call: 'news', stype:'deleteAllData', 'DIDS': $scope.hId.roles}}).success(function(response){
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
                alert("Please select a news");
            }


        };


        $scope.deleteData = function(did) {
            var c = confirm("Are you sure you wish to delete?");
            if(c)
            {
                $scope.fullPageLoader = 1;
                $http({method: 'POST', url: 'cms/news/deleteNews', data: {call: 'news', stype:'deleteData', 'news_id': did}}).success(function(response){
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

        $scope.updateStatus = function(news_id, news_current_status, $index) {
            //alert(news_id + "===" + news_current_status)
            if(parseInt(news_current_status) == parseInt(1))
            {
                news_now_status = 0;
            }
            else
            {
                news_now_status = 1;
            }

            $scope.allNewsArray[$index].loading = true;


            //// update ajax here ========
            $http({method: 'POST', url: 'cms/news/updateStatus', data: {call: 'news', stype:'updateStatus', news_id: news_id, news_status: news_now_status}}).success(function(response){
                //console.log(response);
                // swal({
                //   title: "Success", 
                //   icon: "success",
                //   timer: 3000 
                // });
                $scope.allNewsArray[$index].loading = false;
                $scope.allNewsArray[$index].status = news_now_status;


            });


            //console.log($scope.allNewswArray);

        };

        //console.log($scope.allNewsArray);
        $scope.setForHomePage = function(news_id, news_current_home, $index) {

            // if(parseInt(news_current_home) == parseInt(0))
            // {
            //     var foundItem = $filter('filter')($scope.allNewsArray, { home: '1' }, true);


            //     if(parseInt(foundItem.length) >= parseInt(6))
            //     {
            //         alert("You can select only 6 news for home page.");
            //         return false;
            //     }

            // }


            //alert(news_id + "===" + news_current_status)
            if(parseInt(news_current_home) == parseInt(1))
            {
                news_now_home = '0';
            }
            else
            {
                news_now_home = '1';
            }

            $scope.allNewsArray[$index].loading_home = true;


            //// update ajax here ========
            $http({method: 'POST', url: 'cms/news/setForHome', data: {call: 'news', stype:'setForHomePage', news_id: news_id, news_home: news_now_home}}).success(function(response){
                //console.log(response);
                // swal({
                //   title: "Success", 
                //   icon: "success",
                //   timer: 3000 
                // });
                $scope.allNewsArray[$index].loading_home = false;
                $scope.allNewsArray[$index].set_at_homepage = news_now_home;

                //console.log($scope.allNewsArray);

            });  
        };

    }]);
