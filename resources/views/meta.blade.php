<title>{{ !empty($title) ? $title : config('app.name') }}</title>
<meta name="description" content="{{ !empty($description) ? $description : ''}}">
<meta name="keywords" content="{{ !empty($keywords) ? $keywords : '' }}">
 
    <meta property="og:url" content="{{ config('app.url')}}" >
    <meta property="og:site_name" content="{{ config('app.name') }}">
    
    <meta property="og:type" content="website">    
    <meta property="og:image" content="{{ asset('logo.png') }}">        
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:description" content="Actively managed, true exposure for the New Economy">
        <meta name="twitter:card" content="Actively managed, true exposure for the New Economy" />
    <meta name="twitter:site" content="@truemark"/>