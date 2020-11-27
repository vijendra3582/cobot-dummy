

@if(!empty($generalSetting))
	@if(!empty($generalSetting->facebook_url ))
    	<!-- <a class="ext" data-src="socialPop" data-title="facebook" href="{{ $generalSetting->facebook_url }}"><i class="icon-facebook"></i></a> -->
    	<li>
	        <a class="social-link facebook ext" data-src="socialPop" data-title="facebook" href="{{ $generalSetting->facebook_url }}"><i class="icon-facebook"></i></a>
	    </li>
    @endif
	@if(!empty($generalSetting->linkedin_url ))
	    <!-- <a class="ext" data-src="socialPop" data-title="twitter" href="{{ $generalSetting->facebook_url }}"><i class="icon-twitter"></i></a> -->
	    <li>
	        <a  class="social-link linkedin ext" data-src="socialPop" data-title="linkedIn" href="{{ $generalSetting->linkedin_url }}"><i class="icon-linkedin"></i></a>
	    </li>
	@endif
	@if(!empty($generalSetting->twitter_url))    
    	<!-- <a class="ext" data-src="socialPop" data-title="linkedIn" href="{{ $generalSetting->facebook_url }}"><i class="icon-linkedin"></i></a> -->
    	<li>
	        <a  class="social-link twitter ext" data-src="socialPop" data-title="twitter" href="{{ $generalSetting->twitter_url }}"><i class="icon-twitter"></i></a>
	    </li>
    @endif
@endif

 

    
    
    
 