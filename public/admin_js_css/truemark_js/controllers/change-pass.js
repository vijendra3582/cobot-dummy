angular.module('truemarkApp').controller('changePasswordController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'Upload', function($http, myConfig, $location, $routeParams, $scope, $timeout, Upload){
  
    $scope.dataFrm = {};
    $scope.dataFrm.status = 1;
    $scope.submitProcess = 0; 
    $scope.addTitle = 'Change Password';
     
      $scope.dataFrm = {};
        $scope.dataFrm.status = 1;
        $scope.submitProcess = 0;
        
        $scope.submit = function() {
            $scope.submitProcess = 1;
            $scope.dataFrm.call = 'user';
            $scope.dataFrm.stype = 'changeAdminPassword';
          
            $http({method: 'POST', url: 'cms/changePassword', data: $scope.dataFrm}).success(function(response){
                //alert(response.SUCCESS);                
                  $scope.submitProcess = 2;

                  if(response.SUCCESS == '1')
                  {
                      $scope.submitProcessMsg = '&#x2714; Successfully Changed';

                  }
                  else if(response.SUCCESS == '2')
                  {
                      $scope.submitProcessMsg = 'Both password do not match';
                  }
                  else
                  {
                      $scope.submitProcessMsg = '&#x2718; Sorry Cannot Process Your Request';
                  }

                 
                  $timeout(function(){
                      $scope.submitProcess = 0;
                      if(response.SUCCESS == '1') {
                          swal({
                            title: "Success", 
                            icon: "success",
                            timer: 3000
                            //button: "Ok",
                          });
                          $scope.dataFrm = {};
                          $location.path('/#');
                         
                      }
                  }, 1000);

              });
            
            
        };
      
      
}]);