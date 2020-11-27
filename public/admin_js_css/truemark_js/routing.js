angular.module('truemarkApp').config(['$routeProvider', function($routeProvider){

    $routeProvider
        .when("/welcome", {
            templateUrl: 'admin_js_css/templates/welcome.html',
            controller: 'welcomeController'
        }) 
        .when("/changePassword", {
            templateUrl: 'admin_js_css/templates/changePassword.html',
            controller: 'changePasswordController'
        }) 
        .when("/homeContent", {
            templateUrl: 'admin_js_css/templates/home-content.html',
            controller: 'homeContentController'
        })
        .when("/aboutUs", {
            templateUrl: 'admin_js_css/templates/about-us.html',
            controller: 'aboutUsController'
        })
        .when("/privacyPolicy", {
            templateUrl: 'admin_js_css/templates/privacy_policy.html',
            controller: 'privacyPolicyController'
        })

        .when("/generalSetting", {
            templateUrl: 'admin_js_css/templates/generalSetting.html',
            controller: 'generalSettingController'
        })
        .when('/listContactUs/:page',{
            templateUrl: 'admin_js_css/templates/contactusdb.html',
            controller: 'contactusdbController'
        })
        .when('/listContactUs',{
            redirectTo: '/listContactUs/1/'
        })
        .when('/listSubscribe/:page',{
            templateUrl: 'admin_js_css/templates/subscribedb.html',
            controller: 'subscribedbController'
        })
        .when('/listSubscribe',{
            redirectTo: '/listSubscribe/1/'
        })



        .when('/addTeamMember',{
            templateUrl: 'admin_js_css/templates/teamMember.html',
            controller: 'teamMemberController'
        })
        .when("/addTeamMember/:id", {
            templateUrl: 'admin_js_css/templates/teamMember.html',
            controller: 'teamMemberController'
        })
        .when('/listTeam/:page',{
            templateUrl: 'admin_js_css/templates/listTeamMember.html',
            controller: 'listTeamMemberController'
        })
        .when('/listTeam',{
            redirectTo: '/listTeam/1/'
        })
        .when('/listNews/:page',{
            templateUrl: 'admin_js_css/templates/listNews.html',
            controller: 'listNewsController'
        })
        .when('/listNews',{
            redirectTo: '/listNews/1/'
        })
        .when('/addNews',{
            templateUrl: 'admin_js_css/templates/news.html',
            controller: 'newsController'
        })
        .when("/addNews/:id", {
            templateUrl: 'admin_js_css/templates/news.html',
            controller: 'newsController'
        })
        .when('/newsHomepagePosition',{
            templateUrl: 'admin_js_css/templates/listHomeNews.html',
            controller: 'listHomeNewsController'
        })
        .when('/newsContentDisclosure',{
            templateUrl: 'admin_js_css/templates/newsContentDisclosure.html',
            controller: 'newsDisclosureController'
        })
        .when('/listFunds/:page',{
            templateUrl: 'admin_js_css/templates/listFunds.html',
            controller: 'listFundsController'
        })
        .when('/listFunds',{
            redirectTo: '/listFunds/1/'
        })
        .when('/addFund',{
            templateUrl: 'admin_js_css/templates/fund.html',
            controller: 'fundController'
        })
        .when("/addFund/:id", {
            templateUrl: 'admin_js_css/templates/fund.html',
            controller: 'fundController'
        })
        .when("/fundContentDisclosure", {
            templateUrl: 'admin_js_css/templates/fundContentDisclosure.html',
            controller: 'fundContentDisclosureController'
        })
        .when("/structuredOutcomeEtfDisclosure", {
            templateUrl: 'admin_js_css/templates/fundOutcomeContentDisclosure.html',
            controller: 'fundOutcomeContentDisclosureController'
        })

        /***/
        .when("/addFundFiles/:id", {
            templateUrl: 'admin_js_css/templates/addFundFiles.html',
            controller: 'addFundFilesController'
        })
        .when("/addFundDetails/:id", {
            templateUrl: 'admin_js_css/templates/addFundDetails.html',
            controller: 'addFundDetailsController'
        })
        .when("/addFundDataAndPricing/:id", {
            templateUrl: 'admin_js_css/templates/addFundDataAndPricing.html',
            controller: 'addFundDataAndPricingController'
        })
        .when("/addFundHoldings/:id", {
            templateUrl: 'admin_js_css/templates/addFundHoldings.html',
            controller: 'addFundHoldingsController'
        }) 
        .when("/addFundDistribution/:id", {
            templateUrl: 'admin_js_css/templates/addFundDistribution.html',
            controller: 'addFundDistributionController'
        })
        .when("/addPerformance/:id", {
            templateUrl: 'admin_js_css/templates/addPerformance.html',
            controller: 'addPerformanceController'
        }) 

        /*** outcome period values ***/
        .when("/outcomePeriodValues/:id", {
            templateUrl: 'admin_js_css/templates/addPeriodValues.html',
            controller: 'outcomePeriodValuesController'
        })
        .when("/currentOutcomePeriodValues/:id", {
            templateUrl: 'admin_js_css/templates/currentOutcomePeriod.html',
            controller: 'currentOutcomePeriodValuesController'
        })
        /**** end ****/
        /******/
        .when('/listResources/:page',{
            templateUrl: 'admin_js_css/templates/listResources.html',
            controller: 'listResourceController'
        })
        .when('/listResources',{
            redirectTo: '/listResources/1/'
        })
        .when('/addResource',{
            templateUrl: 'admin_js_css/templates/resource.html',
            controller: 'resourceController'
        })
        .when("/addResource/:id", {
            templateUrl: 'admin_js_css/templates/resource.html',
            controller: 'resourceController'
        })
        .when('/resourceContentDisclosure',{
            templateUrl: 'admin_js_css/templates/resourceContentDisclosure.html',
            controller: 'resourceDisclosureController'
        })
        /**********/
        .when("/sitePopup/:key", {
            templateUrl: 'admin_js_css/templates/sitePopup.html',
            controller: 'sitepopController'
        })
        
        .when("/interim-fund-page", {
            templateUrl: 'admin_js_css/templates/interim-fund-page.html',
            controller: 'interimFundPageController'
        })
        
        
        .otherwise({
            redirectTo: 'welcome'
        });

}]);
