<?php
$appVersion = "1.0.4";
$pagename = strtolower(basename($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=0">
    
    <link rel="icon" href="{{asset('favicon.png')}}" type="image/png"> 
    
    @yield('meta')

    <meta name="google" content="notranslate">
    <meta name="theme-color" content="#0a233f">
    <meta name="msapplication-navbutton-color" content="#0a233f">
    <meta name="apple-mobile-web-app-status-bar-style" content="#0a233f">
    

    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i|Raleway:600,700,900|Heebo:500|Roboto:400,400i,500,700,900&display=swap" rel="stylesheet">
   
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
    <script>
    WebFont.load({
        google: {
            families: ['Playfair Display:400,400i,700,700i', 'Raleway:600,700,900','Heebo:500','Roboto:400,400i,500,700,900']
        }
    });
    </script> -->
    
    <!-- Global site tag (gtag.js) - Google Analytics -->

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-163663179-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-163663179-1');
    </script>

    <!-- Global site tag (gtag.js) - Google Analytics -->

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-166466803-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'UA-166466803-1');
    </script>
    
    
    <link href="{{asset('frontend/css-truemark/app.min.css?v='.config('app.version'))}}" rel="stylesheet"> 
    <link href="{{asset('frontend/css-truemark/new.css?v='.config('app.version'))}}" rel="stylesheet"> 
    <script> </script>
    <style type="text/css">
        .error,.error  + label:after{
            border: 1px solid red;
        }
        .chbox .error  + label:before{
            border-color: red;
        }
        .error:focus{
            border: 1px solid red;
            box-shadow: 0 0 0 0.2rem rgba(255, 0, 59, 0.25);
        }
        label.error {
            display: none!important;
        }
        @media (min-width: 767px){
            .news-listing>li>a h3 {
                margin-right: 0;
                width: 120px;
           }
        }

        .header-top {
            padding: 10px 0;
        }
        .brand-logo img, .brand-logo svg {
            width: 100px;
            height: 100%;
        }
        .footer-main .footer-logo img {
            width: 120px;
            height: 100%;
        }
        @media (max-width: 1199px){
            .section-hero {
                 padding: 145px 0 115px;
            }
            .header-top {
                padding: 10px 0;
            }
        }
        @media (max-width: 767px){
            .section-hero {
                padding: 145px 0 70px;
            }
        }
    </style>
    @yield('stylesheets')
</head>

<body class="page-load {{ \Request::route()->getName() == 'home' ? 'home-page' : 'inner-page'}}" ng-app="mainApp" >
    <!--[if lte IE 11]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    @php 
        $generalSetting = App\GeneralSetting::first(); 
    @endphp

    <div class="wrapper clearfix">
        <header class="header-main clearfix compensate-for-scrollbar">
            <div class="clearfix header-top">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="{{ route('home')}}" class="brand-logo">
                                <img src="{{asset('logo.svg')}}" alt="TrueMark">
                            </a>
                        </div>
                        <div class="col">
                            <a href="javascript:void(0)" class="hm d-xl-none">
                                <div class="hamburger">
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <div class="social d-none d-xl-flex align-items-center justify-content-end ">
                                <a data-fancybox data-src="#subscribePop" href="javascript:void(0)">SUBSCRIBE</a>
                                <ul class="list-unstyled d-flex m-0 mx-n2">
                                   @include("include-social",['generalSetting' => $generalSetting])
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="main-nav d-xl-flex justify-content-center">
                <ul class="list-unstyled nav m-0">
                    <li class=""><a href="{{ route('home')}}">Home</a></li>
                    <li><a class="ext" data-src="advPop" href="https://truemarkinvestments.com/">About Us</a></li>

                    <!-- Fund links -->
                    @php
                        $funds = \App\Fund::where('status',config('constants.const_active'))
                                    ->where('is_outcome_product',config('constants.const_inactive'))
                                    ->orderBy('position','ASC')
                                    ->get();

                        $countOutcome = \App\Fund::where('status',config('constants.const_active'))
                                                ->where('is_outcome_product',config('constants.const_active'))
                                                ->orderBy('position','ASC')
                                                ->count();
                    @endphp
                    @if((!empty($funds) && count($funds)>0 ) || ($countOutcome > 0))
                    <li class="{{ (\Request::route()->getName() == 'fund-detail') ? 'active' : ''}}">
                        <a href="javascript:void(0)">Our ETFs</a>
                        <div class="submenu">
                            <ul>
                                @if($countOutcome > 0)
                                <li><a href="{{ url('/products')}}">            
                                    <span>Structured Outcome ETFs</span>
                                </a></li>
                                @endif      
                                
                                @foreach($funds as $index=>$fund)
                                <li>
                                    <a href="{{ route('fund-detail',['fundKey' => $fund->url_key ])}}"> 
                                        
                                            <span>{{$fund->fund_name}}</span>
                                            <span>{{ $fund->sub_title }}</span>
                                        
                                    </a>
                                </li>
                                @endforeach
                                
                                                        
                            </ul>
                        </div>
                    </li>
                    @endif

                    <!-- news links -->
                    @php
                        $news_count = App\News::where('status', config('constants.const_active'))->count();
                    @endphp
                    @if($news_count > 0)
                        <li class="{{ (\Request::route()->getName() == 'news') ? 'active' : ''}}"><a href="{{ route('news') }}">In The News</a></li>
                    @endif

                    <!-- Resource -->
                    @php
                        $res_count = App\Resource::where('status', config('constants.const_active'))->count();
                    @endphp
                    @if($res_count > 0)
                        <li class="{{ (\Request::route()->getName() == 'resources-list') ? 'active' : ''}}"> <a href="{{ route('resources-list') }}">Resources</a></li>
                    @endif

                    <!-- contact us -->
                    <li>
                        @if(\Request::route()->getName() == 'home')
                            <a href="javascript:void(0)" data-scrollto="contact">Contact Us</a>
                        @else
                            <a href="javascript:void(0)" class="contactPop">Contact Us</a>
                        @endif
                    </li>
                </ul>
            </nav>
        </header>
        <main>
