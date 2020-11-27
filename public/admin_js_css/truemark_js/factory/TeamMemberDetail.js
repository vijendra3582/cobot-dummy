////////////////============== FOR ETF ===========================
angular.module('truemarkApp').factory('TeamMemberDetail',['$http', 'myConfig', '$location', '$routeParams', function($http, myConfig, $location, $routeParams) {
    var factory = {};
    factory.getTeamMemberDetail = function(team_id) {
        if(team_id) {
            // list only 1
            var objData = {};
            objData.stype = 'getTeamMemberDetail';
            objData.id = team_id;
            objData.showAll = "NO";
            objData.call = "teamMember";
            return $http({method: 'POST', url: 'cms/team/list/1', data:objData});
        } else {
            alert($location.search().search_title);
            // list all
            var objData = {};

            objData.search_news_title = $location.search().search_news_title || "";
            objData.search_status = $location.search().search_status || "";

            objData.call = "teamMember";
            objData.stype = 'getTeamMemberDetail';
            objData.showAll = "YES";
            objData.page = $routeParams.page;


            //console.log(objData);
            return $http({method: 'POST', url: 'cms/team/list/1', data:objData});
        }

        //return $routeParams.id;
    };



    return factory;
 }]);
