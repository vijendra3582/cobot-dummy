////////////////============== FOR ETF ===========================
angular.module('truemarkApp').factory('ResourceDetail',['$http', 'myConfig', '$location', '$routeParams', function($http, myConfig, $location, $routeParams) {
    var factory = {};
    factory.getResourceDetail = function(resource_id) {
        if(news_id) {
            // list only 1
            var objData = {};
            objData.stype = 'getResourceDetail';
            objData.id = resource_id;
            objData.showAll = "NO";
            objData.call = "resource";
            return $http({method: 'POST', url: 'cms/resource/list/1', data:objData});
        } else {
            // alert($location.search().search_title);
            // list all
            var objData = {};

            objData.search_title = $location.search().search_title || ""; 
            objData.search_status = $location.search().search_status || "";

            objData.call = "resource";
            objData.stype = 'getResourceDetail';
            objData.showAll = "YES";
            objData.page = $routeParams.page;


            //console.log(objData);
            return $http({method: 'POST', url: 'cms/resource/list/1', data:objData});
        }

        //return $routeParams.id;
    };



    return factory;
 }]);
