angular.module('truemarkApp').controller('currentOutcomePeriodValuesController', ['$http', 'myConfig', '$location', '$routeParams', '$scope', '$timeout', 'FundDetail', 'Upload', '$filter', '$q', function($http, myConfig, $location, $routeParams, $scope, $timeout, FundDetail , Upload, $filter, $q){
      $scope.dataFrm = {}; 
      $scope.fullPageLoader = 0;
      $scope.toTimestamp = function(date) {
          return new Date(date).getTime();
      };

      var guid = function() {
        var s4 = function() {
          return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
        }
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
          s4() + '-' + s4() + s4() + s4();
      }

      $scope.fullPageLoader = 1;
      $scope.fuploading = 0;


      var promise = FundDetail.getFundDetail($routeParams.id);

      promise.then(
          function(payload) {
              // console.log(payload);
              if(parseInt(payload.data.length) > parseInt(0))
              { 
                  $scope.dataFrm.fund_id = payload.data.data.id;
                  $scope.dataFrm.fund_name = payload.data.data.fund_name ? payload.data.data.fund_name.replace(/\\/g,'') : '';
                  $scope.dataFrm.fund_ticker = payload.data.data.fund_ticker ? payload.data.data.fund_ticker.replace(/\\/g,'') : '';
                  $scope.dataFrm.display_current_outcome_period_values = (parseInt(payload.data.data.display_current_outcome_period_values) == parseInt(0) ? false:true);
                  $scope.dataFrm.f_override_current_outcome = (parseInt(payload.data.data.f_override_current_outcome) == parseInt(0) ? false:true);
                  $scope.dataFrm.current_outcome_period_text = payload.data.data.current_outcome_period_text ? payload.data.data.current_outcome_period_text.replace(/\\/g,'') : '';  
              }
              else
              {
                    //location.href = "fund.php";
                    $location.path('/welcome');
              }


          },
          function(errorPayload) {
              console.log('failure loading fund', errorPayload);
          }


      );

      var objList = {};
      objList.call = 'fund';
      objList.stype = 'listFundCurrentOutcomePeriod';
      objList.fund_id = $routeParams.id;

      // console.log(objList);

      var promise_list = $http({method: 'POST', url: 'cms/fund/current-outcome-period', data: objList});

       promise_list.then(
          function(payload) {
              $scope.dataFrm.id = payload.data.id;
              $scope.dataFrm.s_p_year_start_value = payload.data.s_p_year_start_value;
              $scope.dataFrm.etf_starting_nav = payload.data.etf_starting_nav;
              $scope.dataFrm.treasury_yield = payload.data.treasury_yield;
              $scope.dataFrm.total_buffer = payload.data.total_buffer;
              $scope.dataFrm.etf_current_price = payload.data.etf_current_price;
              $scope.dataFrm.s_p_reference_value = payload.data.s_p_reference_value;
              $scope.dataFrm.upside_participation = payload.data.upside_participation ? payload.data.upside_participation.replace(/\\/g,'') : '';


              $scope.dataFrm.etf_current_price = payload.data.etf_current_price ? payload.data.etf_current_price.replace(/\\/g,'') : '';
              $scope.dataFrm.period_return = payload.data.period_return ? payload.data.period_return.replace(/\\/g,'') : '';
              $scope.dataFrm.spx_period_return = payload.data.spx_period_return ? payload.data.spx_period_return.replace(/\\/g,'') : '';
              $scope.dataFrm.remaining_buffer = payload.data.remaining_buffer ? payload.data.remaining_buffer.replace(/\\/g,'') : '';
              $scope.dataFrm.downside_before_buffer = payload.data.downside_before_buffer ? payload.data.downside_before_buffer.replace(/\\/g,'') : '';
              $scope.dataFrm.downside_to_floor_of_buffer = payload.data.downside_to_floor_of_buffer ? payload.data.downside_to_floor_of_buffer.replace(/\\/g,'') : '';
              $scope.dataFrm.remaining_outcome_period = payload.data.remaining_outcome_period ? payload.data.remaining_outcome_period.replace(/\\/g,'') : '';

              $scope.dataFrm.override_etf_current_price = (parseInt(payload.data.override_etf_current_price) == parseInt(0) ? false:true);
              $scope.dataFrm.override_period_return = (parseInt(payload.data.override_period_return) == parseInt(0) ? false:true);
              $scope.dataFrm.override_spx_period_return = (parseInt(payload.data.override_spx_period_return) == parseInt(0) ? false:true);
              $scope.dataFrm.override_remaining_buffer = (parseInt(payload.data.override_remaining_buffer) == parseInt(0) ? false:true);
              $scope.dataFrm.override_downside_before_buffer = (parseInt(payload.data.override_downside_before_buffer) == parseInt(0) ? false:true);
              $scope.dataFrm.override_downside_to_floor_of_buffer = (parseInt(payload.data.override_downside_to_floor_of_buffer) == parseInt(0) ? false:true);
              $scope.dataFrm.override_remaining_outcome_period = (parseInt(payload.data.override_remaining_outcome_period) == parseInt(0) ? false:true);
              

          },
          function(errorPayload) {
              console.log('failure loading fund', errorPayload);
          }
      );


         $scope.fullPageLoader = 0;
         $scope.submitProcess = 0;
        
         // console.log($scope.dataFrm);

        $scope.submit = function() {

            $scope.submitProcess = 1;
            $scope.dataFrm.call = 'fund';
            $scope.dataFrm.stype = 'saveFundCurrentOutcomeValues';

            $http({method: 'POST', url: 'cms/fund/current-outcome-period/save', data: $scope.dataFrm}).success(function(response){
                  $scope.submitProcess = 2;

                  if(response.SUCCESS == '1')
                  {
                      $scope.submitProcessMsg = '&#x2714; Successfully Saved';
                      swal({
                        title:'Success',
                        text:'Successfully Saved',
                        icon:'success',
                        timer:3000
                      });
                  }
                  else
                  {
                      $scope.submitProcessMsg = '&#x2718; Sorry Cannot Process Your Request';
                  }

                  console.log(response);
                  $timeout(function(){
                      $scope.submitProcess = 0;
                      if(response.SUCCESS == '1') {
                            location.href = "#/currentOutcomePeriodValues/" + $routeParams.id + "/?A=" + Math.random();
                          //$location.path("/listIndex/1");
                          //$scope.dataFrm = {};
                      }
                  }, 3000);

              });


        };

    


    $scope.activateCurrentOutcomePeriod = function() {
        //console.log($scope.dataFrm.f_active_fund_holdings);

        $http({method: 'POST', url: 'cms/fund/outcome-period/activateCurrentOutcome', data: {call: 'fund', stype:'activateCurrentOutcomePeriod', fund_id: $routeParams.id, display_current_outcome_period_values: $scope.dataFrm.display_current_outcome_period_values}}).success(function(response){
              //console.log(response);
              swal({
                title:'Success',
                icon:'success',
                timer:3000
              });
        });
    };






}]);
