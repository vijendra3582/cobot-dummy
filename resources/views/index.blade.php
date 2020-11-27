@extends('layout')
@section('meta')
    @include('meta',[
            'title'=> $homeContent->meta_title,
            'description'=> $homeContent->meta_description,
            'keywords'=> $homeContent->meta_keyword
        ])
@endsection

@section('content')

<section class="clearfix section section-hero d-flex align-items-center">
    <div class="hero-bg rellax" style="background-image: url({{ !empty($homeContent->banner_img) ? asset('home-content-uploads/'.$homeContent->banner_img) : '' }});"></div>
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-auto">
                <div class="hero-content">
                <div class="title stagger"> 
                    <h1>
                      @php $headings = explode(' ', $homeContent->banner_heading); @endphp
                        @foreach($headings as $h=>$heading)<span>{{$heading}}</span>@endforeach
                    </h1>
                    <span></span>
                </div>
                <div class="subtitle">
                    <p>{{$homeContent->banner_text}}</p>
                </div>
                <div class="btn-wrap">
                    <a data-scrollto="etfs" href="javascript:void(0)" class="btn btn-outline-secondary">OUR ETFs</a>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($homeContent->enable_dark_banner == true && !empty(trim($homeContent->banner_footer_txt)))
<section class="section section-strip clearfix">
  <div class="container text-center text-large">
     <!-- <p>TrueShares Structured Outcome July ETF is launching July 1. </p>
     <p>For more information, please email us at info@true-shares.com or call 877.774.TRUE</p> -->
     <p>{!! $homeContent->banner_footer_txt !!}</p>
  </div>
</section>
@endif

<section class="clearfix section section-intro">
    <div class="container anim">
        <div class="content">
            {!! $homeContent->description !!}
        </div>
    </div>
</section>


@if((!empty($funds) && count($funds) > 0) || ($countStructuredEtf > 0) )

<section id="etfs" class="clearfix section section-etfs bg-gray text-center">
    <div class="container anim">
        <div class="clearfix section-title">
            <h2>{{ !empty($fundContent) && !empty($fundContent->title) ? $fundContent->title : 'OUR ETFs' }}</h2>
        </div>
        <div class="content">
            <p> {{ !empty($fundContent) && !empty($fundContent->description) ? $fundContent->description : 'Led by experienced industry specialists, our actively managed ETFs seek to deliver true, targeted exposure to the nascent asset classes of the New Economy.' }} </p>
        </div>

        <div class="row gutter-large mx-n2 etfs-row justify-content-center pt-3">
            @if($countStructuredEtf > 0)
            <div class="col-md-4 px-2 my-4">
                <div class="card h-100 card-default card-primary">
                    <div class="card-heading">
                        <h3>{{ $outcomeContent->structured_outcome_title }}</h3>
                        <p>{{ $outcomeContent->structured_outcome_subtitle }}</p>
                    </div>
                    <div class="card-body">
                      {{ $outcomeContent->structured_outcome_short_desc }}
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ url('/products') }}" class="btn btn-secondary">LEARN MORE</a>
                    </div>
                </div>
            </div> 
            @endif
            @foreach($funds as $fkey => $fund)
            <div class="col-md-4 px-2 my-4">
                <div class="card h-100 card-default card-primary">
                    <div class="card-heading">
                        <h3>{{ $fund->fund_name }}</h3>
                        <p>{{ $fund->sub_title }}</p>
                    </div>
                    <div class="card-body">
                         {!! $fund->fund_short_description !!}
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('fund-detail',['fundKey'=> $fund->url_key]) }}" class="btn btn-secondary">LEARN MORE</a>
                    </div>
                </div>
            </div>
            @endforeach
            
        </div>
    </div>
</section>

@endif


@if(!empty($news) && count($news) > 0) 
<section id="news" class="clearfix section section-news text-center">
    <div class="container anim">
        <div class="clearfix section-title">
            <h2>{{ !empty($newsDisclosure) ? $newsDisclosure->title : 'IN THE NEWS' }}</h2>
        </div>

        <div class="row mt-n2 mx-10">
            
            @foreach($news as $nkey => $nvalue)
            <div class="col-md-4 my-4 px-10">
                <div class="card h-100 card-default card-secondary">
                    <div class="card-heading">
                        <div class="date">{{ strtoupper(date('M d, Y', strtotime($nvalue->date)))}}</div>
                        <h3>{{ $nvalue->publication }}</h3>
                    </div>
                    <div class="card-body">
                        <p>{{ $nvalue->title }}</p>
                    </div>
                    <div class="card-footer text-center">
                        
                        @if(!empty($nvalue->news_url) && $nvalue->news_type == 'URL')
                            <a class="btn btn-primary ext" data-src="newsPop" href="{{$nvalue->news_url}}" target="_blank">
                        @elseif(!empty($nvalue->news_file) && $nvalue->news_type == 'FILE') 
                            <a class="btn btn-primary" href="{{ route('file-iframe',['type' => 'news','key'=> encrypt(asset(config('constants.news_folder').$nvalue->news_file))]) }}" target="_blank">
                        @elseif(!empty($nvalue->video_file) && $nvalue->news_type == 'VIDEO')
                            <a class="btn btn-primary" data-fancybox href="{{asset(config('constants.news_folder').$nvalue->video_file)}}">
                        @else
                            <a class="btn btn-primary" disabled>
                        @endif 
                            {{ !empty($nvalue->link_title) ? strtoupper($nvalue->link_title) : 'READ MORE'}} 
                        </a>
                    </div>
                </div>
            </div> 
            @endforeach
            
        </div>

        <div class="btn-wrap text-center">
            <a href="{{ route('news') }}" class="btn btn-secondary">VIEW ALL</a>
        </div>

    </div>
</section>
@endif


<section id="contact" class="clearfix section section-contact bg-gray">
    <div class="container anim">
        <div class="clearfix section-title">
            <h2>GET IN TOUCH</h2>
        </div>
        <div class="content text-center">
            <p>{{ strip_tags($generalSetting->contact_us_header) }}</p>
        </div>


    @if($generalSetting->enable_map_button == config('constants.const_active') && !empty($generalSetting->map_img) && file_exists(public_path(config('constants.upload_folder').$generalSetting->map_img)) )    
     <div class="mapbox text-center d-flex align-items-center justify-content-center" style="background-image:url({{ asset(config('constants.upload_folder').$generalSetting->map_background_img ) }})">

           <a href="{{ asset(config('constants.upload_folder').$generalSetting->map_img) }}" data-fancybox class="btn btn-primary btn-lg">{{$generalSetting->button_txt}}</a>
     </div>
     @endif
      <div class="form-wrap">
          <div class="address-info clearfix">
              <div class="row">
                  <div class="col">
                      <h3>{{ $generalSetting->company_name }}</h3>
                  </div>
                  <div class="col-auto ml-auto">
                      <div class="social">
                        <ul class="list-unstyled d-flex justify-content-lg-end m-0 mr-n2">
                            @include("include-social",['generalSetting' => $generalSetting])
                        </ul>
                      </div>
                  </div>
              </div>

            <ul class="c-links d-flex flex-wrap justify-content-between list-unstyled">
                <li>
                    <a target="_blank" href="{{ $generalSetting->location_url }}">
                        <i class="icon-address"></i>{{ strip_tags($generalSetting->address) }}
                    </a>
                </li>
                <li>
                    <a href="tel:{{ $generalSetting->telephone }}">
                        <i class="icon-phone"></i>
                        {{ $generalSetting->telephone }}
                    </a>
                </li>
                <li>
                    <a href="mailto:{{ $generalSetting->info_email }}">
                        <i class="icon-email"></i>
                        {{ $generalSetting->info_email }}
                    </a>
                </li>
            </ul>

          </div>
          <div class="thank-you-class">
            <form class="contact-us-form" method="post" autocomplete="off">
                @csrf
                <ul class="clearfix list-unstyled d-flex flex-wrap check-group">
                   <li>
                       <div class="chbox">
                          <input id="a1" class="investor" type="checkbox" name="investor_type[]" value="FINANCIAL PROFESSIONAL">
                          <label for="a1">I AM A FINANCIAL PROFESSIONAL</label>
                       </div>
                   </li>
                   
                   <li>
                      <div class="chbox">
                         <input id="a2" class="investor" type="checkbox" name="investor_type[]" value="INDIVIDUAL INVESTOR">
                         <label for="a2">I AM AN INDIVIDUAL INVESTOR</label>
                      </div>
                  </li>
                  <li class="align-self-center pl-md-0 col-12 col-md-auto"><small>(Please select one)</small></li>
                </ul>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <input type="text" class="form-control input-class" placeholder="Name (Required)" name="name">
                        </div>
                    </div>
                     <div class="col-12 col-sm-6">
                          <div class="form-group">
                              <input type="email" class="form-control input-class" placeholder="Email (Required)" name="email">
                          </div>
                      </div>
                      <div class="col-12 col-sm-6">
                          <div class="form-group">
                              <input type="text" class="form-control " placeholder="Phone" name="phone">
                          </div>
                      </div>
                    <div class="col-12">
                      <div class="form-group">
                         <textarea class="form-control input-class" placeholder="Message (Required)" name="message" id="" rows="10"></textarea>
                      </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <div class="chbox">
                                <input id="b1" type="checkbox" name="subscribe">
                                <label for="b1">Check this box to receive company and fund news</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center btn-wrap submit-loader"> 
                      <button class="btn btn-secondary">SUBMIT</button> 
                </div>
            </form>
          </div>
      </div>

      <div class="form-note clearfix">
          <p>{!! $generalSetting->contact_us_footer !!}</p>
      </div>

    </div>
</section>


@endsection

@section('disclosure')
    @if(!empty($homeContent->disclosure))
        @include('disclosure',['disclosure' => $homeContent->disclosure])
    @endif
@endsection

@section('scripts')
<script type="text/javascript">

  $(".contact-us-form").validate({
    ignore: [],
    rules: {
        name: {
            required: true,
            nospace:true
        },
        message:{
            required: true,
            nospace:true
        },
        // compound rule
        email: {
          required: true,
          email: true,
          nospace:true
        },
        'investor_type[]':'required'

      }, 
      errorPlacement: function(error, element) {
          if (element.attr("type") == "checkbox" && element.attr('name') == "investor_type[]" ){ 
                  $("[name='investor_type[]']").addClass('error');  
               
          }
          else{
              // error.insertAfter(element);
              return true;
          }
      },
      submitHandler: function(form) { 
        $('.submit-loader').html('<span class="spinner"></span>');
        var url = "{{route('contact-us')}}"; 
        var formData = new FormData($(form)[0]); 
        jQuery.ajax({
          url: url,
          method: 'POST',
          processData: false,
          contentType: false,
          data: formData, 
          success: function(response){
            console.log(response);   
               if(response.status == 'success'){
                  $('.thank-you-class').html('<h1 class="mt-5"> Thank you for contacting us. We will get back to you soon. </h1>');
               }
          },
          error: function (err) { console.log(err); }
        });
      } 
  });

</script>
@endsection