@extends('layout')
@section('meta')
    @include('meta',[
            'title'=>  !empty($content->meta_title) ?$content->meta_title : 'Product List',
            'description'=> $content->meta_description,
            'keywords'=> $content->meta_keywords
        ])
@endsection
@section('content')

<!-- /** write html here **/ -->
<svg width="0" height="0" class="hidden">
  <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7.335 13.383" id="arrow-right">
    <path d="M.643 13.383l6.692-6.692L.643 0 0 .643l6.048 6.048L0 12.74z"></path>
  </symbol>
</svg>
<section class="clearfix section section-hero page-hero table-hero d-flex align-items-center">
    <div class="hero-bg rellax" style="background-image: url({{asset(config('constants.fund_folder').$content->banner_img )}});"></div>
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-auto">
                <div class="hero-content">
                    <div class="title">
                        <h1>{{$content->title}}</h1>
                        <span></span>
                    </div>
                    <div class="subtitle">
                        <p>{{$content->sub_title}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if(!empty(trim($content->banner_footer_txt)))
<section class="section section-strip clearfix">
  <div class="container text-center text-medium">
     {!! $content->banner_footer_txt !!}  
  </div>
</section>
@endif

@php
    $outcome_date = \DB::table('sp_usbanks')->orderBy('date','DESC')->first();
@endphp

<section class="clearfix section page-product-table bg-gray">
    <div class="container">
        <div class="data-table-wrap">
            <div class="row pb-4">
                <div class="col">
                    <div class="data-date">As of {{date('m/d/Y', strtotime($outcome_date->date))}}</div>
                </div>
                <div class="col-auto">
                    <a href="{{route('download-structuredETF-csv')}}" class="btn btn-secondary">Download table data (CSV)</a>
                </div>
            </div>
            @if(count($funds) > 0)
            <div class="table-responsive" >
                <table class="table" id="products">
                    <thead>
                        <tr>
                            <th>Ticker</th>
                            <th>Name</th>
                            <th>Series</th>
                            <th>Fund Price</th>
                            <th>Period Returns</th>
                            <th>Index</th>
                            <th>Index Return</th>
                            <th>Est. Upside Market Participation Rate</th>
                            <th>Remaining Buffer</th>
                            <th>ETF Downside to Buffer</th>
                            <th>S&P Downside to Floor of Buffer</th>
                            <th>Remaining Outcome Period</th>
                            <th class="text-center">Prospectus</th>                           
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($funds as $f=>$fund)

                        @php 
                            $fundOutcome = \App\Fund::outcomeETF($fund->fund_ticker);
                        @endphp
                            @if(!empty($fundOutcome))
                            <tr>
                                <td class="f-name"><a href="{{ route('fund-detail',['fundKey' => $fund->url_key ])}}" style="color: #ffffff;">
                                    {{$fund->fund_ticker}}
                                </a>    
                                </td>
                                <td style="min-width:100px;"><a href="{{ route('fund-detail',['fundKey' => $fund->url_key ])}}">{{ $fund->fund_name}}</a></td>
                                <td>{{ $fund->product_series}}</td>
                                <td>${{round($fundOutcome['fund_price'],2)}}</td>
                                <td>{{$fundOutcome['fund_return']}}</td>
                                <td style="min-width:90px;">{{$fundOutcome['index']}}</td>
                                <td>{{$fundOutcome['index_return']}}</td>
                                <td>{{$fundOutcome['upside_participation']}}</td>
                                <td>{{$fundOutcome['remaining_buffer']}}</td>
                                <td>{{$fundOutcome['downside_to_buffer']}}</td>
                                <td>{{$fundOutcome['s_p_downside_to_floor_of_buffer']}}</td>
                                <td>{{$fundOutcome['remaining_outcome_period']}} days</td>
                                <td class="p-link text-center">

                                    
                                @if(!empty($fund->prospectusFile))
                                    @if(!empty($fund->prospectusFile->file_path))
                                        <a href="{{route('fund-detail-files',['fundKey'=> $fund->url_key, 'fundFile' => $fund->prospectusFile->url_key ])}}" target="_blank">
                                           <svg class="icon"><use xlink:href="#arrow-right"></use></svg>
                                        </a>
                                    @else
                                        <a href="javascript:void(0)">
                                           <svg class="icon"><use xlink:href="#arrow-right"></use></svg>
                                        </a>    
                                    @endif
                                @else
                                    <a href="javascript:void(0)">
                                       <svg class="icon"><use xlink:href="#arrow-right"></use></svg>
                                    </a>  
                                @endif    


                                </td>
                            </tr>
                            
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div>
                <h3>No structured outcome etf found.</h3>
            </div>
            @endif                            
            <div class="data-info">
                {!! $content->description !!}
            </div>
        </div>

    </div>
</section>

@endsection
 
@section('disclosure')
@php $disclosure = !empty(trim($content->disclosure)) ? $content->disclosure :'' ; @endphp
@include('disclosure',['disclosure' => $disclosure]) 
@endsection

<!-- /*****/ -->
@section('scripts')
<!-- /** scripts here **/ -->
<script>
$(document).ready(function(){
    var tbody = $("#products tbody");

    if (tbody.children().length == 0) {
        tbody.html("<tr><td colspan='11' class='text-center'>Structured Outcome ETF not found.</td></tr>");
    }
})
    
</script>
@endsection