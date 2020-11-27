////////////////============== FOR ETF ===========================
angular.module('truemarkApp').factory('contactusdbDetail',['$http', 'myConfig', '$location', '$routeParams', function($http, myConfig, $location, $routeParams) {
    var factory = {};
    factory.getContactusdbDetail = function(id) {
        if(id) {
            // list only 1
            var objData = {};
            objData.stype = 'getContactusdbDetail';
            objData.id = id;
            objData.showAll = "NO";
            objData.call = "contactusdb";
            return $http({method: 'POST', url: 'cms/contactusdb/list/1', data:objData});
        } else {
            //alert($location.search().search_title);
            // list all
            var objData = {};

            // objData.search_news_title = $location.search().search_news_title || "";
            // objData.search_status = $location.search().search_status || "";

            objData.call = "contactusdb";
            objData.stype = 'getContactusdbDetail';
            objData.showAll = "YES";
            objData.page = $routeParams.page;


            //console.log(objData);
            return $http({method: 'POST', url: 'cms/contactusdb/list/1', data:objData});
        }

        //return $routeParams.id;
    };



    return factory;
 }]);
