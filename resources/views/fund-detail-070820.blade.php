@extends('layout')
@section('meta')
    @include('meta',[
            'title'=> !empty($fund['meta_title']) ? $fund['meta_title'] : config('app.name'),
            'description'=> !empty($fund['meta_description']) ? $fund['meta_description'] : '',
            'keywords'=> !empty($fund['meta_keyword']) ? $fund['meta_keyword'] : ''
        ]) 
@endsection

@section('stylesheets')
<script src="{{asset('angular_modules/angular/angular.min.js')}}"></script>
<script src="{{asset('angular_modules/angular-sanitize/angular-sanitize.min.js')}}"></script>
<style>
[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
  display: none !important;
}
</style>
@endsection

@section('content')

<section class="clearfix section section-hero etf-hero d-flex align-items-center">
    <div class="hero-bg rellax" style="background-image: url({{ !empty($fund['banner_image']) ? $fund['banner_image_disp'] : '' }});"></div>
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-auto">
                <div class="hero-content">
                    <div class="title">
                        <h1>{{ $fund['fund_name']}}</h1>
                        <span></span>
                    </div>
                    <div class="subtitle">
                        <p>{{ $fund['sub_title']}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@if(!empty($fund['fund_data']) && count($fund['fund_data']) > 0)
<section class="clearfix section page-news pt-4 mt-2">
    <div class="container anim">
        <div class="data-row animContent">
            <div class="row flex-lg-row-reverse">
                <div class="col-12 col-md">
                    <div class="content fund-content pt-3">
                        {!! $fund['fund_description'] !!}
                    </div>
                </div>

                <div class="col-12 col-md-auto">
                    <div class="fund-data fund-info">
                        <div class="table-responsive">
                            <table class="table table-small">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="table-head">FUND DETAILS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fund['fund_data'] as $key=>$detail)
                                    <tr>
                                        <td>{{ $detail['data_head']}}</td>
                                        <td>{{ $detail['data_value']}}</td>
                                    </tr>
                                    @endforeach 
                                </tbody>
                            </table>
                        </div>                      
                        <div class="row">
                            <div class="col-12">
                                <div class="data-info">
                                    {!! $fund['fund_detail_notes'] !!}
                                </div>
                            </div>
                        </div>                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<section class="clearfix section page-news bg-gray pt-5" style="border-top: 2px solid #D7D7D7;">
    <div class="container anim">
        <div class="row flex-md-row-reverse mb-5">
            
            @if(!empty($fund['fund_data_pricing']) && count($fund['fund_data_pricing'])> 0 )
            <div class="col-12 col-md">
                <div class="fund-data">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="2" class="table-head">FUND DATA &amp; PRICING</th>
                                </tr>
                            </thead>
                            <tbody> 
                                @foreach($fund['fund_data_pricing'] as $key=>$data_pricing)
                                <tr>
                                    <td>{{ $data_pricing['data_head']}}</td>
                                    <td>{{ $data_pricing['data_value']}}</td>
                                </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="data-info">
                                @php
                                    if(!empty($fund['fund_data_and_pricing_as_of_date']) && $fund['fund_data_and_pricing_as_of_date'] != '0000-00-00')
                                        $pricing_date = date('m/d/Y', strtotime($fund['fund_data_and_pricing_as_of_date']));
                                    else{
                                         
                                        $stmt_black = \DB::table('fund_daily_nav_usbanks')->where('fund_ticker', $fund['fund_ticker'])->orderBy('rate_date','DESC')->first();

                                        if(!empty($stmt_black))
                                        {
                                            $tfield = $stmt_black->rate_date;
                                            $pricing_date = date('m/d/Y',strtotime($tfield));
                                        }
                                        else
                                        {
                                            $pricing_date = "xx/xx/xxxx";
                                        }
                                    }
                                @endphp
                                <p>Data as of {{ $pricing_date }}</p>
                                {!! $fund['fund_data_pricing_notes'] !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if(!empty($fund['fund_files']) && count($fund['fund_files']) > 0)
            <div class="col-12 col-md-auto">
                <div class="docs-link h-md-100">
                    <h3>FUND DOCUMENTS</h3>
                    <ul class="list-unstyled mb-0">
                        @foreach($fund['fund_files'] as $key=>$file)
                            @if(!empty($file['file_path']))
                                @php
                                    if(strtolower($file['file_type']) == 'pdf')
                                        $icon_class = '';
                                    else
                                        $icon_class = 'doc';
                                @endphp 
                        
                                <li class="{{$icon_class}}">
                                    <a href="{{route('fund-detail-files',['fundKey'=> $fund['url_key'], 'fundFile' => $file['url_key'] ])}}" target="_blank">   
                                        {{ $file['label_name'] }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>

<!-- /** outcome period values **/ ===============================================
            ==============================================================-->
    @if($fund['is_outcome_product'] == true)         
        @if($fund['display_outcome_period_values'] == true)    
            <div class="data-row animContent  mb-5">
                <div class="table-wrap">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="2" scope="col" class="table-head">OUTCOME PERIOD VALUES </th>
                                    <th class="table-subhead pr-0" colspan="3" align="right">{{ $fund['outcome_period_values_text']}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="nobg subhead-row">
                                    <th scope="col" class="text-center text-nowrap">ETF Starting NAV/Period Return</th>
                                    <th scope="col" class="text-center text-nowrap">SPX Index Reference Price</th>
                                    <th scope="col" class="text-center text-nowrap">Downside Buffer </th>
                                    <th scope="col" class="text-center text-nowrap">Est. Upside Market Participation Rate</th>
                                    <th scope="col" class="text-center text-nowrap">Days Remaining</th>
                                </tr>

                                @if(!empty($fund['fund_outcome']) && count($fund['fund_outcome']) > 0)
                                    @foreach($fund['fund_outcome'] as $key=>$dVal)
                                    <tr>
                                        <td class="text-center">{!! !empty($dVal['etf_starting_nav_period_return']) ? $dVal['etf_starting_nav_period_return'] : '$0.00/0.00%' !!}</td>
                                        
                                        <td class="text-center">{!! !empty($dVal['spx_index_reference_price']) ? $dVal['spx_index_reference_price'] : '0' !!}</td>
                                        
                                        <td class="text-center">{!! !empty($dVal['downside_buffer']) ? $dVal['downside_buffer'] : '0.00%' !!}</td>
                                        
                                        <td class="text-center">{!! !empty($dVal['expected_upside_participation']) ? $dVal['expected_upside_participation'] : '0.00%'  !!}</td>

                                        <td class="text-center">{!! !empty($dVal['days_remaining']) ? $dVal['days_remaining'] : '0'  !!}</td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td class="text-center">&nbsp;</td>
                                    <td class="text-center">&nbsp;</td>
                                    <td class="text-center">&nbsp;</td>
                                    <td class="text-center">&nbsp;</td>
                                    <td class="text-center">&nbsp;</td>
                                </tr>
                                @endif
                                 
                            </tbody>
                        </table>
                    </div>
                </div> 
            </div>
        @endif


        @php 
            $daily_nav_usbank = \DB::table('fund_daily_nav_usbanks')->where('fund_ticker', $fund['fund_ticker'])->orderBy('rate_date','DESC')->first();

            $sp_usbanks = \DB::table('sp_usbanks')->orderBy('date','DESC')->first();
        
        if(($fund['display_current_outcome_period_values'] == true)){
            $TOTAL_BUFFER = (float)$fund['fund_current_outcome'][0]['total_buffer'];
            $TREASURY_YIELD = (float)$fund['fund_current_outcome'][0]['treasury_yield'];
            $ETF_STARTING_NAV = (float)$fund['fund_current_outcome'][0]['etf_starting_nav'];
            $S_P_YEAR_START_VALUE = (float)$fund['fund_current_outcome'][0]['s_p_year_start_value'];
            $S_P_REFERENCE_VALUE = (float)$fund['fund_current_outcome'][0]['s_p_reference_value'];
            if($fund['fund_current_outcome'][0]['override_etf_current_price'] == true)
            {
                $ETF_CURRENT_NAV = $fund['fund_current_outcome'][0]['etf_current_price'];
            }else{
                $daily_nav_usbank = \DB::table('fund_daily_nav_usbanks')->where('fund_ticker', $fund['fund_ticker'])->orderBy('rate_date','DESC')->first();


                if(empty($daily_nav_usbank)){
                    return false;
                }

                $ETF_CURRENT_NAV = !empty($daily_nav_usbank) ? (float)$daily_nav_usbank->nav : 0.00;

            }    
            

            $YTD_SP_RETURN =!empty($sp_usbanks) ? (float)($sp_usbanks->ytd_return /100) : 0.00;
            
            $OUTCOME_START_DATE = !empty($fund['launch_date']) ? date('Y-m-d', strtotime($fund['launch_date'])) : date('Y').'-07-01';

            $date = date('Y-m-d');
            $CURRENT_DATE =  $date < date('Y-m-d',strtotime($OUTCOME_START_DATE)) ? $OUTCOME_START_DATE : $date ;
            
            $OUTCOME_END_DATE = !empty($fund['end_date']) ? date('Y-m-d', strtotime($fund['end_date'])) : date('Y-m-d', strtotime($OUTCOME_START_DATE.'+1 years'));
       
            $PERIOD_RETURN = ($ETF_STARTING_NAV != 0) ? ($ETF_CURRENT_NAV/$ETF_STARTING_NAV)-1 : '0.00';

            if($fund['fund_current_outcome'][0]['override_spx_period_return'] == true){
                $SPX_PERIOD_RETURN = $fund['fund_current_outcome'][0]['spx_period_return'];
            }else{
                $CURRENT_SP_LEVEL = $S_P_YEAR_START_VALUE * (1+ $YTD_SP_RETURN);

                $SPX_PERIOD_RETURN = $S_P_REFERENCE_VALUE != 0 ? ($CURRENT_SP_LEVEL/$S_P_REFERENCE_VALUE) -1 : '0.00';

            }
            

            $INDEX_RETURN = !empty($SPX_PERIOD_RETURN) ? (float)$SPX_PERIOD_RETURN : '0.00';
             
            if($INDEX_RETURN >= 0 ){

                $REMAINING_BUFFER = $TOTAL_BUFFER != 0 ?  (-1)*$TOTAL_BUFFER : '0.00';

                $DOWNSIDE_TO_BUFFER = ($ETF_STARTING_NAV - $ETF_CURRENT_NAV)/ $ETF_CURRENT_NAV;

            
            }elseif($INDEX_RETURN < 0){

                $REMAINING_BUFFER = ((-1)*($PERIOD_RETURN-($SPX_PERIOD_RETURN - $TOTAL_BUFFER))*$ETF_STARTING_NAV/$ETF_CURRENT_NAV)+$TREASURY_YIELD;

                $DOWNSIDE_TO_BUFFER = '0.00';

            }else{

                $REMAINING_BUFFER = '0.00';

                $DOWNSIDE_TO_BUFFER = '0.00';
            } 
            
            $DATE_DIFF = strtotime($OUTCOME_END_DATE) - strtotime($CURRENT_DATE);

            $REMAINING_OUTCOME_PERIOD = round($DATE_DIFF / (60 * 60 * 24)) + 1 ;

        @endphp 
            
                <div class="data-row animContent  mb-5">
                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th colspan="2" scope="col" class="table-head">CURRENT OUTCOME PERIOD VALUES </th>
                                        <th class="table-subhead pr-0" colspan="3" align="right"> {{ !empty($fund['current_outcome_period_text']) ? $fund['current_outcome_period_text'] : 'As of '. date('m/d/Y', strtotime($sp_usbanks->date))}}</th>
                                         
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="nobg subhead-row">
                                        <th scope="col" class="text-center text-nowrap">ETF Current Price/Period Return</th>
                                        <th scope="col" class="text-center text-nowrap">SPX Period Return</th>
                                        <th scope="col" class="text-center text-nowrap">Remaining Buffer </th>
                                        <th scope="col" class="text-center text-nowrap">ETF Downside to Buffer</th>
                                        <th scope="col" class="text-center text-nowrap">Remaining Outcome Period</th>
                                    </tr>

                                    
                                    <tr>
                                        <!-- <td class="text-center">${{round($ETF_CURRENT_NAV, 2)}}/-8.74%</td>
                                        <td class="text-center">-14.25%</td>
                                        <td class="text-center">10.46%</td>
                                        <td class="text-center">0.00%</td>
                                        <td class="text-center">168 days</td> -->

                                        <td class="text-center">

                                        ${{round($fund['fund_current_outcome'][0]['override_etf_current_price'] == (int)1 ? $fund['fund_current_outcome'][0]['etf_current_price'] : $ETF_CURRENT_NAV, 2)}}/{{round($fund['fund_current_outcome'][0]['override_period_return'] == (int)1 ? $fund['fund_current_outcome'][0]['period_return'] : $PERIOD_RETURN*100, 2)}}%

                                        </td>
                                        <td class="text-center">{{ round($fund['fund_current_outcome'][0]['override_spx_period_return'] == (int)1 ? $fund['fund_current_outcome'][0]['spx_period_return'] : $SPX_PERIOD_RETURN*100 ,2) }}%</td>
                                        <td class="text-center">{{ round($fund['fund_current_outcome'][0]['override_remaining_buffer'] == (int)1 ? $fund['fund_current_outcome'][0]['remaining_buffer'] : $REMAINING_BUFFER*100,2) }}%</td>
                                        <td class="text-center">{{ round($fund['fund_current_outcome'][0]['override_downside_before_buffer'] == (int)1 ? $fund['fund_current_outcome'][0]['downside_before_buffer'] : $DOWNSIDE_TO_BUFFER*100,2) }}%</td>
                                        <td class="text-center">{{ (int)$fund['fund_current_outcome'][0]['override_remaining_outcome_period'] == (int)1 ? $fund['fund_current_outcome'][0]['remaining_outcome_period'] : $REMAINING_OUTCOME_PERIOD }} days</td>
                                    </tr>
                                    
                                     
                                </tbody>
                            </table>
                        </div>
                    </div> 
                </div>
            

        @php      }       @endphp
    @endif




        <!-- Performance section start -->
        @php
            $stmtPerformanceDates = \DB::table('fund_monthly_performance_usbanks')->select(DB::raw('DISTINCT(date) AS as_of_date'))->where('fund_ticker', 'LIKE', '%'.$fund['fund_ticker'].'%')->orderBy('date','DESC')->get();

            if(count($stmtPerformanceDates) > 0){
                $stmtPerformanceDates = $stmtPerformanceDates->toArray();
                $rowPerformanceDates = $stmtPerformanceDates;
            }

            $performance_date = isset($stmtPerformanceDates[0]->as_of_date) ? $stmtPerformanceDates[0]->as_of_date : '';
        @endphp
        @if($fund['f_active_performance'] == config('constants.const_active'))
            @if($fund['f_override_performance'] == config('constants.const_inactive'))

            <div class="fund-data clearfix mb-5" ng-controller="myPerformanceCtrl">
                <div class="table-responsive" style="position: relative;">
                    <div class="spinner" ng-if="showPerformanceDataLoader" ng-cloak></div>              
                    <table class="table" ng-show="!showPerformanceDataLoader" ng-cloak>
                        <thead>
                            <tr>
                                <th scope="col" colspan="2" class="table-head">PERFORMANCE</th>
                                <th class="table-subhead pr-0" colspan="3" align="right" ng-if="performance_date">Quarter end returns as of @{{performance_date|date:'MM/dd/yyyy' }}</th>
                            </tr>
                            <tr class="border2x subhead-row ff-heading">
                                <th scope="col" colspan="3" class="table-th">&nbsp;</th>
                                <th align="right" colspan="@{{performanceChartArray.CUMULATIVE_ARRAY[0].length }}" scope="col" class="table-th" >AVG. ANNUALIZED </th>
                            </tr>
                        </thead>
                        <tbody> 
                            <tr ng-repeat="(key, value) in performanceChartArray.CUMULATIVE_ARRAY track by $index" class="@{{key ==0 ? 'bodyhead' : '' }}">
                                <td ng-repeat="(k, v) in value  track by $index" class="text-center" nowrap>
                                    @{{v}}@{{($index != 0 && $parent.$index != 0) ? '%':''}}
                                </td>
                            </tr>

                            <tr ng-if="performanceChartArray.AVERAGE_ANNUAL_ARRAY.length == 0" ng-cloak>
                                <td>No Performance Found</td>
                            </tr>                      
                            <!-- <tr  class="bodyhead">                            
                                <td class="table-th" width="20%">
                                    &nbsp;
                                </td>
                              
                                <td class="text-center table-th" width="20%">
                                    Quarter
                                </td>
                                <td class="text-center table-th" width="20%">
                                    1 Year
                                </td>
                                <td class="text-center table-th" width="20%">
                                    3 Year
                                </td>
                                <td class="text-center table-th" width="20%">
                                    Since Inception
                                </td>
                            </tr>                       
                            <tr>                            
                                <td class="">
                                    FUND NAV
                                </td>                           
                                <td class="text-center">
                                    &nbsp;
                                </td>
                                <td class="text-center">
                                    &nbsp;
                                </td>
                                <td class="text-center">
                                    &nbsp;
                                </td>
                                <td class="text-center">
                                    &nbsp;
                                </td>
                            </tr>   
                            <tr>                            
                                <td class="">
                                    MARKET PRICE
                                </td>                           
                                <td class="text-center">
                                    &nbsp;
                                </td>
                                <td class="text-center">
                                    &nbsp;
                                </td>
                                <td class="text-center">
                                    &nbsp;
                                </td>
                                <td class="text-center">
                                    &nbsp;
                                </td>
                            </tr> 
                            <tr>                            
                                <td class="">
                                    BENCHMARK INDEX
                                </td>                           
                                <td class="text-center">
                                    &nbsp;
                                </td>
                                <td class="text-center">
                                    &nbsp;
                                </td>
                                <td class="text-center">
                                    &nbsp;
                                </td>
                                <td class="text-center">
                                    &nbsp;
                                </td>
                            </tr>   --> 
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="data-info">
                            {!! $fund['performance_description'] !!}
                        </div>
                    </div>
                </div>
            </div>
            @else
                @php 
                    $cumulativePerformance = json_decode($fund['cumulative_performance_table'], true);
                    $calendarYrPerformance = json_decode($fund['calendar_yr_performance_table'], true);
                    
                    if($fund['cumulative_performance_display'] == config('constants.const_active') && count($cumulativePerformance) > 1 && count($cumulativePerformance[0]['colArray']) > 1){
                        $cumulativeDisplay = 1;
                    }
                    else{
                        $cumulativeDisplay = 0;
                    }

                    if($fund['calendar_yr_perfromance_display'] == config('constants.const_active') && count($calendarYrPerformance) > 1 && count($calendarYrPerformance[0]['colArray']) > 1){
                        $calendarDisplay = 1;
                    }
                    else{
                        $calendarDisplay = 0;
                    }
                @endphp
                @if($cumulativeDisplay == 1 || $calendarDisplay == 1)
                <div class="fund-data clearfix mb-5">
                    <div class="table-responsive" style="position: relative;">       
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" colspan="2" class="table-head">PERFORMANCE</th>
                                    <th class="table-subhead pr-0" colspan="3" align="right">{{ !empty($fund['cumulative_performance_text']) ? $fund['cumulative_performance_text']  : ''}}</th>
                                </tr>
                                <tr class="border2x subhead-row ff-heading">
                                    <th scope="col" colspan="3" class="table-th">&nbsp;</th>
                                    <th align="right" colspan="5" scope="col" class="table-th" >AVG. ANNUALIZED </th>
                                </tr>
                            </thead>
                            <tbody> 
                                @foreach($cumulativePerformance as $key=>$performance)
                                    <tr class="{{$key == 0 ? 'bodyhead' : ''}}">
                                        @foreach($performance['colArray'] as $k=> $val)
                                            <td class="{{$key == 0 ? 'table-th' : '' }} {{$k != 0 && $key == 0 ? ' text-center' :''}}" {{ $key == 0 ? 'width=20%' : ''}}>{!! !empty($val['value']) ? $val['value'] : '&nbsp;' !!}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                        
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="data-info">
                                {!! $fund['performance_description'] !!}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endif
        @endif
     
    <!-- /************************************/     -->

        @if(!empty($fund['f_active_fund_distribution']) && $fund['f_active_fund_distribution'] == config('constants.const_active'))
        <div class="data-row animContent  mb-5">
            <div class="table-wrap">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="4" scope="col" class="table-head">DISTRIBUTION DETAIL </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="nobg subhead-row">
                                <th scope="col" class="text-center text-nowrap">EX-Date</th>
                                <th scope="col" class="text-center text-nowrap">Record Date</th>
                                <th scope="col" class="text-center text-nowrap">Payable Date </th>
                                <th scope="col" class="text-center text-nowrap">Amount</th>
                            </tr>

                            @if(!empty($fund['fund_distribution']) && count($fund['fund_distribution']) > 0)
                                @foreach($fund['fund_distribution'] as $key=>$dVal)
                                <tr>
                                    <td class="text-center">{!! !empty($dVal['ex_date']) ? $dVal['ex_date'] : '&nbsp;' !!}</td>
                                    <td class="text-center">{!! !empty($dVal['record_date']) ? $dVal['record_date'] : '&nbsp;' !!}</td>
                                    <td class="text-center">{!! !empty($dVal['payable_date']) ? $dVal['payable_date'] : '&nbsp;' !!}</td>
                                    <td class="text-center">{!! !empty($dVal['amount']) ? $dVal['amount'] : '&nbsp;'  !!}</td>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                            </tr>
                            @endif
                             

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @if(!empty($fund['premium_discount_file']) && !empty($fund['is_premium_discount']) == config('constants.const_active'))
                        @php
                            $fileUrl = route('fund-detail-files',['fundKey'=> $fund['url_key'],'fundFile'=>'premium-discount' ]);
                        @endphp
                    <a href="{{ $fileUrl }}" target="_blank"
                        class="btn btn-secondary">DOWNLOAD PREMIUM DISCOUNT INFORMATION</a>
                    @endif
                    
                </div>
            </div>
        </div>
        @endif


        <!-- /********** holdings *********/ -->
        @if(!empty($fund['f_active_fund_holdings']) && $fund['f_active_fund_holdings']== config('constants.const_active'))

        @php
            if($fund['f_override_fund_holdings'] == config('constants.const_active')){
                $fundHoldings = $fund['fund_holdings'];
                
                if(!empty($fund['holdings_file'])){
                    $fundHoldingFileUrl = route('fund-detail-files',['fundKey'=> $fund['url_key'],'fundFile'=>'fund-holdings' ]);
                }else{
                    $fundHoldingFileUrl = '';
                } 

                if(!empty($fund['fund_holdings_as_of_date']))
                    $fund_holding_date = date('m/d/Y', strtotime($fund['fund_holdings_as_of_date']));
                else
                    $fund_holding_date = 'xx/xx/xxxx';   

            }else{
                $stmtHoldDateUSBank = \DB::table('fund_holdings_usbanks')->where('account', $fund['fund_ticker'])->max('date'); 
                 
                $date = $stmtHoldDateUSBank;
                
                $stmtHoldDataUSBank = \DB::table('fund_holdings_usbanks')->select('date','security_description as name',
                         'stock_ticker as identifier',
                         'cusip as cusip', 
                          \DB::raw("REPLACE(weightings, '%', '') AS percentage_of_net_assets"),
                          'shares as shares_held' , 
                          'market_value' )
                ->where('account', $fund['fund_ticker'])
                ->where('date', $date)
                ->orderByRaw("CAST(market_value AS DECIMAL(10,2)) DESC") 
                ->limit(10)->get();

                if(count($stmtHoldDataUSBank) > 0){
                    $fundHoldings = $stmtHoldDataUSBank;
                    $fundHoldings = json_decode(json_encode($fundHoldings), true);
                    $fund_holding_date = date('m/d/Y', strtotime($date));
                    $fundHoldingFileUrl = route('download-holding-usbanks',['fund'=> $fund['url_key'] ]);
                }else{
                    $fund_holding_date = 'xx/xx/xxxx';
                    $fundHoldingFileUrl = '';
                }
                
            }
        @endphp

        <div class="data-row mb-0 animContent" data-sr-id="10"
            style="visibility: visible; opacity: 1; transition: opacity 1s cubic-bezier(0.5, 0, 0, 1) 0.3s;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" colspan="6" class="table-head">TOP 10 HOLDINGS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="subhead-row">
                            <th scope="col" style="min-width: 190px;">Percentage Of Net Assets</th>
                            <th scope="col" class="text-nowrap w-50">Name</th>
                            <th scope="col" class="text-nowrap">Ticker </th>
                            <th scope="col" class="text-nowrap">CUSIP </th>
                            <th scope="col" class="text-nowrap">Shares Held</th>
                            <th scope="col" class="text-nowrap">Market Value</th>
                        </tr>
                        @if(!empty($fundHoldings) && count($fundHoldings) > 0)
                            @foreach($fundHoldings as $key=>$fhVal)
                            <tr>
                                <td class="">{{$fhVal['percentage_of_net_assets'] ? $fhVal['percentage_of_net_assets'] : 'xxxx'}}</td>
                                <td class="">{{$fhVal['name'] ? $fhVal['name'] : 'xxxx'}}</td>
                                <td class="text-center">{{$fhVal['identifier'] ? $fhVal['identifier'] : 'xxxx'}}</td>
                                <td class="text-center">{{$fhVal['cusip'] ? $fhVal['cusip'] : 'xxxx'}}</td>
                                <td class="text-center">{{$fhVal['shares_held'] ? $fhVal['shares_held'] : 'xxxx'}}</td>
                                <td class="text-center">{{$fhVal['market_value'] ? $fhVal['market_value'] : 'xxxx'}}</td>
                            </tr>    
                            @endforeach
                        @else
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="text-center">&nbsp;</td>
                            <td class="text-center">&nbsp;</td>
                            <td class="text-center">&nbsp;</td>
                            <td class="text-center">&nbsp;</td>
                        </tr>    
                        @endif  
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                @if(!empty($fundHoldingFileUrl))
                    <a href="{{$fundHoldingFileUrl}}" class="btn btn-secondary">DOWNLOAD FULL HOLDINGS</a>
                @endif
                </div>
                 
                <div class="col-12 col-md-6 pt-2 pt-md-0">
                    <div class="data-info text-md-right">
                        <p>Data as of {{$fund_holding_date}}. <?php echo strip_tags($fund['holdings_notes']) ?> </p> 
                    </div>
                </div>              
            </div>
        </div>
        @endif

    </div>
</section>
 

@endsection

@section('disclosure')
@if(!empty($fund['fund_disclosure']))  
    @include('disclosure',['disclosure' => $fund['fund_disclosure']]) 
@endif 
@endsection

@section('scripts')

<script type="text/javascript">
(function(){
    /*var app = angular.module("mainApp", [], function($interpolateProvider) {
        $interpolateProvider.startSymbol('<==');
        $interpolateProvider.endSymbol('==>');
    }); */

    var app = angular.module("mainApp",[]); 
    
    angular.module("mainApp").controller("myPerformanceCtrl", ['$scope', '$http', '$timeout', function($scope, $http, $timeout){
       $scope.performance_date = "{{$performance_date}}";

       $scope.showPerformanceDataLoader = true;
       $scope.changePerformanceData = function() {
            $scope.showPerformanceDataLoader = true;
           
            $scope.performanceChartArray= new Array();
            $http({method: 'POST', url: '{{route("ajax-performance")}}', data: {type:'getPerformanceData', performance_date: $scope.performance_date, fticker: '{{$fund["fund_ticker"]}}'}}).then(function(response){
                console.log(response);
                $scope.showPerformanceDataLoader = false;
                $scope.performanceChartArray= response.data;
            });
       };

       $scope.changePerformanceData();



       /*$('.selecter').selectric({
            onChange: function(element) {
                //console.log(element.value);
                $scope.performance_date = element.value;
                $scope.changePerformanceData();
            }
        });*/

       var performanceDatesArray = <?php echo json_encode($stmtPerformanceDates) ?>;
       $scope.loadAverageAnnual = function() {
            for(ik = 0; ik < performanceDatesArray.length; ik++) {
                // console.log(performanceDatesArray[ik].as_of_date);
                var dt = new Date(performanceDatesArray[ik].as_of_date);
                var mo = dt.getMonth()+1;

                if(mo === 3 || mo === 6 || mo === 9 || mo === 12) {
                    //console.log(performanceDatesArray[ik].as_of_date);  
                    $scope.performance_date = performanceDatesArray[ik].as_of_date;
                    $scope.changePerformanceData();
                    // $("#performance_date").val($scope.performance_date);
                    // $('.selecter').selectric('refresh');
                    break;  
                }
            }
       }


       $scope.loadCumulative = function() {
            for(ik = 0; ik < performanceDatesArray.length; ik++) {
                // console.log(performanceDatesArray[ik].as_of_date);
                var dt = new Date(performanceDatesArray[ik].as_of_date);
                var mo = dt.getMonth()+1;
 
                //console.log(performanceDatesArray[ik].as_of_date);  
                $scope.performance_date = performanceDatesArray[ik].as_of_date;
                $scope.changePerformanceData();
                // $("#performance_date").val($scope.performance_date);
                // $('.selecter').selectric('refresh');
                break;  
                 
            }
       }
  }]);
})();
</script>
@endsection