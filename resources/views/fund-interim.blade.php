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



<section class="clearfix section page-news pt-4 mt-2">
    <div class="container anim">
        <div class="data-row animContent">
            <div class="row flex-lg-row-reverse">
                <div class="col-12 col-md">
                    <div class="content fund-content pt-3">
                        {!! $fund['fund_description'] !!}
                        <p></p>
                        {!! $fund['fund_launch_description'] !!}
                        <!-- <p>JULZ seeks to provide investors with structured outcome exposure to the S&P 500. The intent is to provide a significant level of equity market upside participation with a measure of downside risk mitigation.</p>
<p>The TrueShares Structured Outcome ETF Series utilizes a “buffer protect” options strategy, seeking to provide
investors with returns (before fees and expenses) that track those of the S&P 500 Price Index while seeking to
offer a 10% downside buffer over a 12-month investment period.</p>
<p>The JULZ defined investment period period begins on July 1, 2020 and resets exactly 12 months later.* The
strategy is implemented through the purchase and sale of options on the S&P 500 Price Index or an ETF that
tracks the the S&P 500 Price Return Index. The intent of the ETFs in the series is to provide a significant level of
equity market upside participation with a measure of downside risk mitigation.</p>
<p></p> 
                        <h3 style="font-size:16px;color:#2f5363;font-weight:900;margin-bottom:10px;">Launching July 1.</h3>
                        <h4 style="font-size:14px;color:#2f5363;font-weight:900;">For more information, please email us at info@true-shares.com or call 877.774.TRUE.</h4>-->
                    </div>
                </div>

                <div class="col-12 col-md-auto">
                    @if(!empty($fund['fund_data']) && count($fund['fund_data']) > 0)    
                    <div class="fund-data fund-info mb-4">
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
                    @endif                          
                    @if(!empty($fund['fund_files']) && count($fund['fund_files']) > 0)
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
                    @endif
                </div>


            </div>
        </div>
    </div>
</section>


<!-- <section class="clearfix section page-news pt-5" >
    <div class="container anim">
        <div class="row flex-md-row mb-5">
                     
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

   
    </div>
</section> -->
 

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

       $scope.showPerformanceDataLoader = true;
       
  }]);
})();
</script>
@endsection