@extends('layout')
@section('meta')
    @include('meta',[
            'title'=> (!empty($disclosure->meta_title) ? $disclosure->meta_title : 'NEWS'),
            'description'=> $disclosure->meta_description,
            'keywords'=> $disclosure->meta_keyword
        ])
@endsection

@section('stylesheet')

@endsection


@section('content')

<section class="clearfix section section-hero page-hero d-flex align-items-center">
    <div class="hero-bg rellax" style="background-image: url({{asset(config('constants.news_folder').$disclosure->banner_img)}});"></div>
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-auto">
                <div class="hero-content">
                    <div class="title">
                        <h1>{{ !empty($disclosure->title) ? $disclosure->title : 'In The News' }}</h1>
                        <span></span>
                    </div>               
                </div>
            </div>
        </div>
    </div>
</section>
<style>
   .disc-sm{
       font-size: 11px;
       text-align: center;
       padding-top:1.25rem;
   }
   .card.card-default .card-footer,
   .card.card-secondary .card-body,
   .card.card-secondary .card-heading{
       padding-left: 20px;
       padding-right: 20px;
   }
 </style>
<section class="clearfix section page-news bg-gray pt-4">
    <div class="container anim">
    @if(count($news) > 0)    

        <div class="row news-row resources-row" id="results">
            @foreach($news as $key=>$val) 
            <div class="col-12 col-md-4">
                <div class="card h-100 card-default card-secondary card-resources text-center">
                    <div class="card-heading">
                        <div class="date">{{strtoupper(date('M d, Y', strtotime($val->date))) }}</div>
                        <h3>{{ $val->publication }}</h3>
                    </div>
                    <div class="card-body">
                        <p>{{ $val->title }} </p>
                    </div>
                    <div class="card-footer text-center">
                        @if(!empty($val->news_url) && $val->news_type == 'URL')
                            <a class="btn btn-primary ext" data-src="newsPop" href="{{$val->news_url}}" >
                        @elseif(!empty($val->news_file) && $val->news_type == 'FILE') 
                            <a class="btn btn-primary" href="{{ route('file-iframe',['type' => 'news','key'=> encrypt(asset(config('constants.news_folder').$val->news_file))]) }}" target="_blank">
                        @elseif(!empty($val->video_file) && $val->news_type == 'VIDEO')
                            <a class="btn btn-primary" data-fancybox href="{{asset(config('constants.news_folder').$val->video_file)}}">
                        @else
                        <a class="btn btn-primary" disabled>
                        @endif
                        {{ !empty($val->link_title) ? $val->link_title : 'READ MORE' }}</a>  
                        <div class="disc-sm">
                            {!! $val->is_disclosure == true ? $val->news_disclosure : '' !!}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div> 
        
         @if($news->currentPage() != $news->lastPage())
            <div class="btn-wrap loading-wrap loader-top text-center pt-4 mt-4 animContent">
                <span class="spinner"></span>
                <a href="javascript:void(0)" class="btn btn-secondary" id="load-more-id">LOAD MORE</a>
            </div> 
        @endif 
    @else
        <div class="row news-row resources-row">
            <div class="col-12 mt-3 text-center">
                <h2>No news found.</h2>
            </div>
        </div>
    @endif
    </div>
</section>
@endsection

@section('disclosure')
@if(!empty($disclosure->disclosure)) 
    @include('disclosure',['disclosure' => $disclosure->disclosure]) 
@endif
@endsection

<!-- /*****/ -->
@section('scripts')

<script>
var page = 1; //track user scroll as page number, right now page number is 1

$('#load-more-id').on('click', function(){
        page++; //page number increment
        load_more(page); //load content
});
function load_more(page){
  $.ajax(
        {
            url: '?page=' + page,
            type: "get",
            datatype: "html",
            beforeSend: function()
            {
                $('.spinner').show();
                $('#load-more-id').hide();
            }
        })
        .done(function(data)
        {
        	// console.log(data.length);
            $('.spinner').hide(); //hide loading animation once data is received
            if(data.length == 0){
                $('#load-more-id').hide();
                $('.loader-top').hide();
                return;
            }

            $('#results').append(data); //append data into #results element
            if(page == '{{$news->lastPage()}}'){
                $('#load-more-id').hide();
                $('.loader-top').hide();
            }else{
                $('#load-more-id').show();
            }
        })
        .fail(function(jqXHR, ajaxOptions, thrownError)
        {
            console.log('No response from server');
        });
 }
</script>

@endsection