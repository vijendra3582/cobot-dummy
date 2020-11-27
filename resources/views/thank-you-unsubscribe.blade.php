@extends('layout')
@section('meta')
    <title>{{ config('app.name') }}</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
@endsection


@section('content')
 
<div class="hero-outer clearfix">
    <section class="section hero-section page-hero">
        <div class="container">
            <div class="page-hero-content">
                <h1 class="title anim-title">
                    <div class="original-title"></div>
                </h1>
            </div>
        </div>
    </section>
</div>

<section class="section section-page">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="content font-sm lh-3x animContent">
                  <h1 class="text-center"> You have been successfully unsubscribed.  </h1>
                </div>
            </div>
        </div>
    </div>
</section>

 

@endsection

