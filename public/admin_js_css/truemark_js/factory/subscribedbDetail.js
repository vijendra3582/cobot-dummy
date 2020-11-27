////////////////============== FOR ETF ===========================
angular.module('truemarkApp').factory('subscribedbDetail',['$http', 'myConfig', '$location', '$routeParams', function($http, myConfig, $location, $routeParams) {
    var factory = {};
    factory.getSubscribedbDetail = function(id) {
        if(id) {
            // list only 1
            var objData = {};
            objData.stype = 'getSubscribedbDetail';
            objData.id = id;
            objData.showAll = "NO";
            objData.call = "subscribedb";
            return $http({method: 'POST', url: 'cms/subscribedb/list/1', data:objData});
        } else {
            //alert($location.search().search_title);
            // list all
            var objData = {};

            // objData.search_news_title = $location.search().search_news_title || "";
            // objData.search_status = $location.search().search_status || "";

            objData.call = "subscribedb";
            objData.stype = 'getSubscribedbDetail';
            objData.showAll = "YES";
            objData.page = $routeParams.page;


            //console.log(objData);
            return $http({method: 'POST', url: 'cms/subscribedb/list/1', data:objData});
        }

        //return $routeParams.id;
    };



    return factory;
 }]);
