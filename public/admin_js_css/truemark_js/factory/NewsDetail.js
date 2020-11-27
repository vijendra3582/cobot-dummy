////////////////============== FOR ETF ===========================
angular.module('truemarkApp').factory('NewsDetail',['$http', 'myConfig', '$location', '$routeParams', function($http, myConfig, $location, $routeParams) {
    var factory = {};
    factory.getNewsDetail = function(news_id) {
        if(news_id) {
            // list only 1
            var objData = {};
            objData.stype = 'getNewsDetail';
            objData.id = news_id;
            objData.showAll = "NO";
            objData.call = "news";
            return $http({method: 'POST', url: 'cms/news/list/1', data:objData});
        } else {
            // alert($location.search().search_title);
            // list all
            var objData = {};

            objData.search_news_title = $location.search().search_news_title || "";
            objData.search_news_publication = $location.search().search_news_publication || "";
            objData.search_status = $location.search().search_status || "";

            objData.call = "news";
            objData.stype = 'getNewsDetail';
            objData.showAll = "YES";
            objData.page = $routeParams.page;


            //console.log(objData);
            return $http({method: 'POST', url: 'cms/news/list/1', data:objData});
        }

        //return $routeParams.id;
    };



    return factory;
 }]);
