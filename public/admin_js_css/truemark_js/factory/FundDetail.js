////////////////============== FOR ETF ===========================
angular.module('truemarkApp').factory('FundDetail',['$http', 'myConfig', '$location', '$routeParams', function($http, myConfig, $location, $routeParams) {
    var factory = {};
    factory.getFundDetail = function(fund_id) {
        if(fund_id) {
            // list only 1
            var objData = {};
            objData.stype = 'getFundDetail';
            objData.id = fund_id;
            objData.showAll = "NO";
            objData.call = "fund";
            return $http({method: 'POST', url: 'cms/fund/edit', data:objData});
        } else { 
            var objData = {}; 

            objData.call = "fund";
            objData.stype = 'getFundDetail';
            objData.showAll = "YES";
            objData.page = $routeParams.page; 
            //console.log(objData);
            return $http({method: 'POST', url: 'cms/funds/list/1', data:objData});
        }

        //return $routeParams.id;
    };



    return factory;
 }]);
