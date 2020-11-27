@extends('layout')
@section('meta')
    @include('meta',[
            'title'=> (!empty($disclosure->meta_title) ? $disclosure->meta_title : 'RESOURCES'),
            'description'=> $disclosure->meta_description,
            'keywords'=> $disclosure->meta_keyword
        ])
@endsection
@section('content')

<section class="clearfix section section-hero page-hero d-flex align-items-center">
    <div class="hero-bg rellax" style="background-image: url({{asset(config('constants.resource_folder').$disclosure->banner_img)}});"></div>
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-auto">
                <div class="hero-content">
                    <div class="title">
                        <h1>{{ !empty($disclosure->title) ? $disclosure->title : 'Resources' }}</h1>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="clearfix section page-resources bg-gray pt-4">
    <div class="container anim">
         
        <div class="tab-wrap">
            @if(count($category) > 0)
            <nav class="tabs clearfix">
                <ul class="list-unstyled d-flex m-0">
                    <li class="{{ empty($sortBy) ? 'active-tab' : ''}}"><a href="{{ route('resources-list') }}">VIEW ALL</a></li>
                    @foreach($category as $ckey => $cvalue)
                    <li class="{{ !empty($sortBy) && ($sortBy == $cvalue->title) ? 'active-tab' : ''}}"><a href="{{ route('resources-list',['sort'=> $cvalue->title]) }}">{{ strtoupper($cvalue->title) }}</a></li>
                    @endforeach
                </ul>              
            </nav>
            @endif
            
            @if(count($resources) > 0)
            <div class="tabs-content">
                <div class="row resources-row" id="results">
                @foreach($resources as $key=>$val)    
                    <div class="col-12 col-md-4">
                        <div class="card card-default card-secondary card-resources text-center">
                            <div class="card-heading">
                                <div class="date">{{ strtoupper(date('M d, Y', strtotime($val->date))) }}</div>
                                <h3>{{ $val->title }}</h3>
                            </div>
                            <div class="card-body">
                                <p>{{ $val->file_type }}</p>
                            </div>
                            <div class="card-footer text-center">
                                @if(!empty($val->resource_url) && $val->resource_type == 'URL')
                                    <a class="btn btn-primary ext" data-src="extPop" href="{{$val->resource_url}}" target="_blank">
                                @elseif(!empty($val->resource_file) && $val->resource_type == 'FILE')
                                    <a class="btn btn-primary" href="{{ route('file-iframe',['type' => 'resource','key'=> encrypt(asset(config('constants.resource_folder'). $val->resource_file))]) }}" target="_blank">    
                                @elseif(!empty($val->video_file) && $val->resource_type == 'VIDEO')
                                    <a class="btn btn-primary "  data-fancybox href="{{asset(config('constants.resource_folder').$val->video_file)}}">
                                @else
                                    <a class="btn btn-primary">
                                @endif
                                        VIEW
                                    </a>
                            </div>
                        </div>
                    </div>
                @endforeach    
                </div>
                @if($resources->currentPage() != $resources->lastPage())
                <div class="row">
                    <div class="col-12">
                        <div class="btn-wrap loading-wrap loader-top text-center pt-4 mt-4 animContent">
                            <span class="spinner"></span>
                            <a href="javascript:void(0)" class="btn btn-secondary" id="load-more-id">LOAD MORE</a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @else
                <h2>Coming Soon...</h2>
            @endif
        </div>
        
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
            url: '?page=' + page+'&sort={{$sortBy}}',
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
            // console.log(data);
            $('.spinner').hide(); //hide loading animation once data is received
            if(data.length == 0){
            	console.log(data.length); 
                $('#load-more-id').hide();
                $('.loader-top').hide();
                return;
            }

            $('#results').append(data); //append data into #results element 

            if(page == '{{$resources->lastPage()}}'){ 
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
