@extends('layout')
@section('meta')
     @include('meta',[
            'title'=> (!empty($policy->meta_title) ? $policy->meta_title : 'PRIVACY POLICY'),
            'description'=> $policy->meta_description,
            'keywords'=> $policy->meta_keyword
        ])
@endsection


@section('content')
 
<section class="clearfix section section-hero page-hero d-flex align-items-center">
    <div class="hero-bg rellax" style="background-image: url({{ !empty($policy->banner_img) ? asset('privacy-policy-upload/'.$policy->banner_img) : '' }});"></div>
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-auto">
                <div class="hero-content">
                    <div class="title">
                        <h1>Privacy Policy</h1>
                        <span></span>
                    </div>               
                </div>
            </div>
        </div>
    </div>
</section>

<section class="clearfix section page-news bg-gray pt-4">
    <div class="container anim">
        {!! $policy->content !!}
    </div>
</section>
  

@endsection

@section('disclosure')
@if(!empty($policy->disclosure))  
    @include('disclosure',['disclosure' => $policy->disclosure])   
@endif
@endsection

@section('scripts')
  <!-- <script>
      document.addEventListener("DOMContentLoaded", function(){
          console.log("hello");
         $(".content table").each(function(){
             $(this).wrap('<div class="table-responsive"></div>')
         })
      });
  </script> -->
@endsection