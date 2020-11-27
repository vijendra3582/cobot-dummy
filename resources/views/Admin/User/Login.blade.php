<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <title>{{config('app.name')}}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}"> 
    <link href="{{asset('admin/css/app.css')}}" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #f4f4f4;
                overflow-y: scroll;
                opacity: 1;
        }
        .login-box{
            max-width: 360px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            position: relative;
            box-shadow: 0 2px 2px 0 rgba(0,0,0,0.16), 0 0 0 1px rgba(0,0,0,0.08);
                margin: 7% auto;
        }
        .message{
            font-size: 13px;
            font-weight: 500;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #ff0000;
        }
        .btn-success{
            width: 100%;
            border-radius: 0;
            background-color: #0a2240;
            border-color: #0a2240;
            font-size: 14px;
            text-transform: uppercase;
        }
        .btn-success:hover, .btn-success:focus, .btn-success:active{
            background-color: #8c9091!important;
            border-color: #8c9091 !important;
            color: #ffffff !important;
        }

        .logo{
                display: block;
                margin: auto;
                width: 100px !important;
        }
    </style>
</head>
<body>

<section class="login-section">
    <div class="login-box">
        <img class="logo" src="{{asset('logo.svg') }}">
        <!-- <h1 style="font-size: 44px;
                    font-weight: 900;
                    text-align: center;
                    color: #0a2240;
                    margin-bottom: -15px;
                ">
                Truemark
        </h1> -->
        <div class="" style="margin-top: 20px;">
            <!-- <p style='color:red'></p> -->
            @if ($errors->any())
            <h2 class="message">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </h2>    
            @elseif(Session::has('message'))
                <h2 class="message">{{ Session::get('message') }}</h2>
            @else
                <h2 class="message">Please login to access</h2>    
            @endif 
            
            <form action="{{url('/cms/login')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="email">User Name:</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{old('username')}}" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" value="">
                </div>

                <input type="hidden" name="remember" value="1">

                <input type="submit" class="btn btn-success" name="submit" value="login">
            </form>
            <!-- <p class="powered">Powered by
                <a href="{{url('/cms')}}" target="_blank" title="Impact Design Solutions"><img style="display: inline-block;" src="{{asset('admin/images/impact.gif')}}" width="85" alt="Impact Design Solutions"></a>
            </p> -->
        </div>
    </div>
</section>
</body>
</html>