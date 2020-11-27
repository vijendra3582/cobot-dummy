</main>
<footer class="footer-main bg-primary">

@php
    $homeContent = App\HomeContent::first();
    $generalSetting = App\GeneralSetting::first();
    $advisorSite = App\SitePopup::where('key','advisorSite')->first();
    $externalSite = App\SitePopup::where('key','externalSite')->first();
    $externalNewsSite = App\SitePopup::where('key','externalNewsSite')->first();
    $socialFacebookSite = App\SitePopup::where('key','socialFacebookSite')->first();
    $socialTwitterSite = App\SitePopup::where('key','socialTwitterSite')->first();
    $socialLinkedInSite = App\SitePopup::where('key','socialLinkedInSite')->first();

@endphp

    <div class="footer-top clearfix" style="background-image:url({{asset('frontend/images-truemark/footer-bg.jpg')}})">
        <div class="container anim">
            <div class="row footer-row">
                <div class="col-md-6 col-lg-auto d-flex align-items-lg-center">
                    <a href="{{ route('home') }}" class="footer-logo">
                        <img src="{{ asset('frontend/images-truemark/footer-logo.svg') }}" alt="TrueMark">
                    </a>
                </div>
                <div class="col-md-6 col-lg">
                    <ul class="footer-address list-unstyled m-0">
                        <li>
                            <a href="tel:{{ $generalSetting->telephone }}">{{ $generalSetting->telephone }}</a>
                        </li>
                        <li>
                            <a href="mailto:{{ $generalSetting->info_email }}">{{ $generalSetting->info_email }}</a>
                        </li>
                        <li><a target="_blank" href="{{  $generalSetting->location_url }}">{!! $generalSetting->address !!}</a></li>
                    </ul>
                </div>
                <div class="col-md-6 col-lg">
                    <div class="copyright">{{ $generalSetting->copyrights}}</div>
                    <div class="social">
                        <ul class="list-unstyled d-flex m-0 ml-n2">
                            @include("include-social",['generalSetting' => $generalSetting])
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-auto">
                    <div class="footer-nav">
                        <ul class="list-unstyled d-flex m-0">
                            <li>
                                <a href="{{ route('home') }}">HOME</a>
                            </li>
                            <li>
                                <a class="ext" data-src="advPop" href="https://truemarkinvestments.com/">ABOUT US</a>
                            </li>

                            <!-- /*****/ -->
                            @php
                                $funds = \App\Fund::where('status',config('constants.const_active'))
                                            ->orderBy('position','ASC')
                                            ->get();
                            @endphp
                            @if(!empty($funds) && count($funds)>0 )
                            <li>
                                @if(\Request::route()->getName() == 'home')
                                <a data-scrollto="etfs" href="javascript:void(0)">OUR ETFS</a>
                                @else
                                  <a href="{{ route('home') }}/#etfs">OUR ETFS</a>
                                @endif
                            </li>
                            @endif

                            <!-- /*****/ -->

                            <!-- news links -->
                            @php
                                $news_count = App\News::where('status', config('constants.const_active'))->count();
                            @endphp
                            @if($news_count > 0)
                            <li>
                                @if(\Request::route()->getName() == 'home')
                                  <a data-scrollto="news" href="javascript:void(0)">IN THE NEWS</a>
                                @else
                                 <a  href="{{ route('news') }}">IN THE NEWS</a>
                                @endif
                            </li>
                            @endif
                            <!-- /*******/ -->
                        </ul>
                    </div>
                    <a data-fancybox data-src="#subscribePop" href="javascript:void(0)"
                        class="btn btn-secondary">SUBSCRIBE</a>
                </div>
            </div>
        </div>
    </div>

    @yield('disclosure')

</footer>

</div> <!-- wrapper end -->

<div style="display: none;max-width: 820px; margin:0 auto" class="w-100 fancypop compensate-for-scrollbar"
    id="contactPop">
    <div class="container-fluid">
        <div class="clearfix section-title">
            <h2>GET IN TOUCH</h2>
        </div>
        <div class="content text-center">
            <p>{!! $generalSetting->contact_us_header !!}</p>
        </div>

        <!-- <div class="mapbox text-center d-flex align-items-center justify-content-center" style="background-image:url({{ asset('frontend/images-truemark/map-bg.png') }})">
           <a href="{{ asset('frontend/images-truemark/TrueMark-TerritoryMapV5.png') }}" data-fancybox class="btn btn-primary btn-lg">CLICK HERE FOR OUR WHOLESALER MAP</a>
       </div> -->
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
                            <i class="icon-address"></i>
                            {{ strip_tags($generalSetting->address) }}
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

            <h2 class="thank-you mt-5" style="display: none; text-align: center;"> Thank you for contacting us. We will get back to you soon. </h2>

             
                <form  class="contact-popup-form" method="post" autocomplete="off">
                    @csrf
                    <ul class="clearfix list-unstyled d-flex flex-wrap check-group">
                        <li>
                            <div class="chbox">
                                <input id="a1" type="checkbox" class="investor" name="investor_type[]" value="FINANCIAL PROFESSIONAL">
                                <label for="a1">I AM A FINANCIAL PROFESSIONAL</label>
                            </div>
                        </li>

                        <li>
                            <div class="chbox">
                                <input id="a2" type="checkbox" class="investor" name="investor_type[]" value="INDIVIDUAL INVESTOR">
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
                                <textarea class="form-control input-class" placeholder="Message (Required)" name="message" id=""
                                    rows="10"></textarea>
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

        <div class="form-note clearfix">
            <p>{!! $generalSetting->contact_us_footer !!}</p>
        </div>

    </div>
</div>



<div id="subscribePop" class="fancypop compensate-for-scrollbar" style="width: 100%;max-width:820px;display: none;">
    <div class="section-contact" id="iwsSubscribeApp">
        <div class="container-fluid">
            <div class="clearfix section-title">
                <h2>SUBSCRIBE</h2>
            </div>
            <div class="content text-center">
                <p>{!! $generalSetting->subscribe_header !!}</p>
            </div>

            <div class="form-wrap subscribe-box clearfix">
                <h2 class="subscribe-thank-you mt-5" style="display: none; text-align: center;"> Thank you for subscribing with us. </h2>
                <form class="subscribe-popup" method="post" autocomplete="off">
                    @csrf
                    <div class="row justify-content-center text-center">
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text" class="form-control input-class" placeholder="Name (Required)" name="name">
                            </div>
                            <div class="form-group mb-2">
                                <input type="email" class="form-control input-class" placeholder="Email (Required)" name="email">
                            </div>
                        </div>
                    </div>
                    <div class="text-center btn-wrap subscribe-loader">
                        <button class="btn btn-secondary">SUBMIT</button>
                    </div>
                </form>
            </div>

            <div class="form-note clearfix">
                <p>{!! $generalSetting->subscribe_footer !!}</p>
            </div> 
        </div>
    </div>
</div>


<!-- Advisor Popup -->
<div id="advPop" class="externalsite fancypop" style="width: 100%;max-width: 820px;display: none;">
    <div class="container-fluid ex-wrap text-center">
        <div class="clearfix section-title">
            <h2>{{ !empty($advisorSite) ? $advisorSite->title : 'ADVISOR SITE' }}</h2>
        </div>
        <div class="pop-content">
            <p>{!! !empty($advisorSite) && !empty($advisorSite->content) ? $advisorSite->content : 'Thank you for visiting the TrueShares ETFs site! You are now being redirected to our advisor site at <br><a href="https://truemarkinvestments.com/">www.TrueMarkInvestments.com</a>.' !!}</p>
        </div>
        <div class="btn-wrap link-box mt-4 pt-0 pb-2 text-center">
            <a href="#" target="_blank" class="btn exurl btn-secondary" onclick="parent.jQuery.fancybox.getInstance().close()">GO</a>
            <a onclick="parent.jQuery.fancybox.getInstance().close()" href="javascript:void(0);" class="btn btn-secondary">
                CANCEL
            </a>
        </div>
    </div>
</div>


<!-- External Popup -->
<div id="extPop" class="externalsite fancypop" style="width: 100%;max-width: 820px;display: none;">
    <div class="container-fluid ex-wrap text-center">
        <div class="clearfix section-title">
            <h2>{{ !empty($externalSite) ? $externalSite->title : 'EXTERNAL SITE' }}</h2>
        </div>
        <div class="pop-content">
            <p>{!! !empty($externalSite) && !empty($externalSite->content) ? $externalSite->content : 'Thank you for visiting the TrueShares ETFs site! You are now being redirected to an external site.' !!}</p>
        </div>
        <div class="btn-wrap link-box mt-4 pt-0 pb-2 text-center">
            <a href="#" target="_blank" class="btn exurl btn-secondary"
                onclick="parent.jQuery.fancybox.getInstance().close()">
                GO
            </a>
            <a onclick="parent.jQuery.fancybox.getInstance().close()" href="javascript:void(0);"
                class="btn btn-secondary">
                CANCEL
            </a>
        </div>
    </div>
</div>



<!-- News Popup -->
<div id="newsPop" class="externalsite fancypop" style="width: 100%;max-width: 820px;display: none;">
    <div class="container-fluid ex-wrap text-center">
        <div class="clearfix section-title">
            <h2>{{ !empty($externalNewsSite) ? $externalNewsSite->title : 'EXTERNAL NEWS SITE' }}</h2>
        </div>
        <div class="pop-content">
            <p>{!! !empty($externalNewsSite) && !empty($externalNewsSite->content) ? $externalNewsSite->content : 'Thank you for visiting the TrueShares ETFs site! You are now being redirected to an external site.' !!}</p>
        </div>
        <div class="btn-wrap link-box mt-4 pt-0 pb-2 text-center">
            <a href="#" target="_blank" class="btn exurl btn-secondary"
                onclick="parent.jQuery.fancybox.getInstance().close()">
                GO
            </a>
            <a onclick="parent.jQuery.fancybox.getInstance().close()" href="javascript:void(0);"
                class="btn btn-secondary">
                CANCEL
            </a>
        </div>
    </div>
</div>

<!-- Twitter Popup -->
<div id="twitterPop" class="externalsite fancypop compensate-for-scrollbar"
    style="width: 100%;max-width: 820px;display: none;">
    <div class="container-fluid ex-wrap text-center">
        <div class="clearfix section-title">
            <h2>TWITTER SITE</h2>
        </div>
        <div class="pop-content">
            <p>Thank you for visiting the TrueShares ETFs site! You are now being redirected to our Twitter site.</p>
        </div>
        <div class="btn-wrap link-box mt-4 pt-0 pb-2 text-center">
            <a href="#" target="_blank" class="btn exurl btn-secondary"
                onclick="parent.jQuery.fancybox.getInstance().close()">
                GO
            </a>
            <a onclick="parent.jQuery.fancybox.getInstance().close()" href="javascript:void(0);"
                class="btn btn-secondary">
                CANCEL
            </a>
        </div>
    </div>
</div>

<!-- Facebook Popup -->
<div id="facebookPop" class="externalsite fancypop compensate-for-scrollbar"
    style="width: 100%;max-width: 820px;display: none;">
    <div class="container-fluid ex-wrap text-center">
        <div class="clearfix section-title">
            <h2>FACEBOOK SITE</h2>
        </div>
        <div class="pop-content">
            <p>Thank you for visiting the TrueShares ETFs site! You are now being redirected to our Facebook site.</p>
        </div>
        <div class="btn-wrap link-box mt-4 pt-0 pb-2 text-center">
            <a href="#" target="_blank" class="btn exurl btn-secondary"
                onclick="parent.jQuery.fancybox.getInstance().close()">
                GO
            </a>
            <a onclick="parent.jQuery.fancybox.getInstance().close()" href="javascript:void(0);"
                class="btn btn-secondary">
                CANCEL
            </a>
        </div>
    </div>
</div>

<!-- LinkedIn Popup -->
<div id="linkedInPop" class="externalsite fancypop compensate-for-scrollbar"
    style="width: 100%;max-width: 820px;display: none;">
    <div class="container-fluid ex-wrap text-center">
        <div class="clearfix section-title">
            <h2>LINKEDIN SITE</h2>
        </div>
        <div class="pop-content">
            <p>Thank you for visiting the TrueShares ETFs site! You are now being redirected to our LinkedIn site.</p>
        </div>
        <div class="btn-wrap link-box mt-4 pt-0 pb-2 text-center">
            <a href="#" target="_blank" class="btn exurl btn-secondary"
                onclick="parent.jQuery.fancybox.getInstance().close()">
                GO
            </a>
            <a onclick="parent.jQuery.fancybox.getInstance().close()" href="javascript:void(0);"
                class="btn btn-secondary">
                CANCEL
            </a>
        </div>
    </div>
</div>

<!-- Social Media Site Popup -->
<div id="socialPop" class="externalsite fancypop compensate-for-scrollbar"
    style="width: 100%;max-width: 820px;display: none;">
    <div class="container-fluid ex-wrap text-center">
        <div class="clearfix section-title">
            <h2 id="socialTitleId">{{ !empty($externalNewsSite) ? $externalNewsSite->title : 'EXTERNAL NEWS SITE' }}</h2>
        </div>
        <div class="pop-content">
            <p id="socialContentId">{{ !empty($externalSite) && !empty($externalSite->content) ? $externalSite->content : 'Thank you for visiting the TrueShares ETFs site! You are now being redirected to an external site.'}}</p>
        </div>
        <div class="btn-wrap link-box mt-4 pt-0 pb-2 text-center">
            <a href="#" target="_blank" class="btn exurl btn-secondary"
                onclick="parent.jQuery.fancybox.getInstance().close()">
                GO
            </a>
            <a onclick="parent.jQuery.fancybox.getInstance().close()" href="javascript:void(0);"
                class="btn btn-secondary">
                CANCEL
            </a>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('frontend/js-truemark/app.bundle.js')}}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
       $.validator.addMethod( "nospace", function( value, element, param ) {
            return $.trim(value).length >= param;
        }, $.validator.format( "Please enter at least {0} characters" ) );
    });      
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        $(".contactPop").on("click", function (e) {
            e.preventDefault();
            $.fancybox.open({
                src: '#contactPop',
                type: 'inline',
                opts: {
                    afterShow: function (instance, current) {
                        console.info('done!');
                    }
                }
            });
        });
    });


    /*$(document).on("click", ".ext", function (e) {
        e.preventDefault();
        var $url = $(this).attr("href");
        var str = $url;
        var dataSrc = $(this).data("src");
        console.log(dataSrc);
        if (!(str.indexOf('idsweb') !== -1)) {
            $.fancybox.open({
                src: '#' + dataSrc,
                type: 'inline',
                opts: {
                    afterShow: function (instance, current) {
                        console.log(current.$content[0].id);
                        $("#" + current.$content[0].id + " .exurl").attr("href", $url);
                    }
                }
            });
            return false;
        } else {
            return true;
        }
    });*/

    $(document).on("click", ".ext", function (e) {
        e.preventDefault();
        var $url = $(this).attr("href");
        var str = $url;
        var dataSrc = $(this).data("src");
        console.log(dataSrc);

        if(dataSrc == 'socialPop'){
            var type = $(this).data('title');
            if(type == 'facebook'){
                $('#socialTitleId').html('{{ $socialFacebookSite->title }}');
                $('#socialContentId').html('{!! $socialFacebookSite->content !!}');
            }

            if(type == 'twitter'){
                $('#socialTitleId').html('{{ $socialTwitterSite->title }}');
                $('#socialContentId').html('{!! $socialTwitterSite->content !!}');
            }

            if(type == 'linkedIn'){
                $('#socialTitleId').html('{{ $socialLinkedInSite->title }}');
                $('#socialContentId').html('{!! $socialLinkedInSite->content !!}');
            }
        }

        if (!(str.indexOf('idsweb7') !== -1)) {
            $.fancybox.open({
                src: '#' + dataSrc,
                type: 'inline',
                opts: {
                    afterShow: function (instance, current) {
                        console.log(current.$content[0].id);
                        $("#" + current.$content[0].id + " .exurl").attr("href", $url);
                    }
                }
            });
            return false;
        } else {
            return true;
        }
    });

</script>

<!-- /****/ -->
<!-- /** for contact us **/ -->
<script type="text/javascript">
$(".input-class").focus(function(){
    if($(this).attr('type') == 'email'){
        if($(this).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null){
            $(this).addClass('error');
        }else{
            $(this).valid();
        }
    }else{
        $val = $(this).val();
        if($val == ''){
            $(this).addClass('error');
        }else{
            $(this).valid();
        }
    }
}) 

$(".input-class").on('keyup',function(){
    if($(this).attr('type') == 'email'){
        if($(this).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null){
            $(this).addClass('error');
        }else{
            $(this).valid();
        }
    }else{
        $val = $(this).val();
        if($val == ''){
            $(this).addClass('error');
        }else{
            $(this).valid();
        }
    }
})


/** contact us popup ***/ 
$("input[type=checkbox]").on('click',function(){  
    if($(this).attr('name') == 'investor_type[]'){ 
     $('.investor').valid();
     $('.investor').removeClass('error').addClass('valid');
    } 
});

    $(".contact-popup-form").validate({
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
        console.log(element.attr('name'));
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
                    
                    $('.thank-you').show();
                    $('.contact-popup-form').hide();
                    setTimeout(function(){ 
                      $("[data-fancybox-close]").trigger('click');
                      $('.thank-you').hide();
                      $('.contact-popup-form').show();
                      $(form).trigger("reset");
                      $('.submit-loader').html('<button class="btn btn-secondary">SUBMIT</button>');
                    }, 3000);
               }
          },
          error: function (err) { console.log(err); }
        });
      } 
    });

/** subscribe popup ***/ 
      
    $(".subscribe-popup").validate({
    rules: {
        name: {
            required: true,
            nospace:true
        },
        // compound rule
        email: {
          required: true,
          email: true,
          nospace:true
        },
        
      }, 

      submitHandler: function(form) { 

        var url = "{{route('subscribe-us')}}"; 
        var formData = new FormData($(form)[0]); 

        $('.subscribe-loader').html('<span class="spinner"></span>');
        jQuery.ajax({
          url: url,
          method: 'POST',
          processData: false,
          contentType: false,
          data: formData, 
          success: function(response){
            // console.log(response);   
               if(response.status == 'success'){
                    
                    $('.subscribe-thank-you').show();
                    $('.subscribe-popup').hide();
                    setTimeout(function(){ 
                      $("[data-fancybox-close]").trigger('click');
                      $('.subscribe-thank-you').hide();
                      $('.subscribe-popup').show();
                      $(form).trigger("reset");
                      $('.subscribe-loader').html('<button class="btn btn-secondary">SUBMIT</button>');
                    }, 3000);
               }
          },
          error: function (err) { console.log(err); }
        });
      } 
    });  


</script>
@yield('scripts')

</body>

</html>