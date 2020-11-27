(function(){
    var app = angular.module("truemarkApp", ['ngRoute','angularUtils.directives.dirPagination', 'ckeditor', 'ngFileUpload', '720kb.datepicker', 'ngSanitize', 'uiSwitch', "checklist-model", 'color.picker', 'ngDialog', 'ui.bootstrap', 'ui.bootstrap.datetimepicker','angular.filter', 'chart.js', 'ngWYSIWYG']);

    app.constant("myConfig", {
        // "ajax_url": "process.php",
        // "upload_url": "upload.php",
    });
    
    app.run(function($rootScope, $http, myConfig, $interval) {
          
        $rootScope.$on('$routeChangeStart', function(e, curr, prev) {
            
             
            console.log("START");
        });
         
        
    });

    app.directive('onlyNum', function() {
          return function(scope, element, attrs) {

             var keyCode = [8,9,37,39,48,49,50,51,52,53,54,55,56,57,96,97,98,99,100,101,102,103,104,105,110];
              element.bind("keydown", function(event) {
                console.log($.inArray(event.which,keyCode));
                if($.inArray(event.which,keyCode) == -1) {
                    scope.$apply(function(){
                        scope.$eval(attrs.onlyNum);
                        event.preventDefault();
                    });
                    event.preventDefault();
                }

            });
         };
      });
 
})();
