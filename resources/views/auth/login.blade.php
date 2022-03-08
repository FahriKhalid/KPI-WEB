<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Key Performance Indicator | Pupuk Kaltim</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400" rel="stylesheet">
</head>
<body class="login">
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="border-login-container">
                <div class="text-center logo-in-login">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('assets/img/ekpi-logo.png') }}" class="responsive-image" height="80">
                    </a>
                </div>
                @include('vendor.flash.message')

                {{ Auth::check() == false ? 'false' : 'true' }}
                <div class="panel panel-default panel-login">
                    <div class="panel-heading text-center">Login</div>
                    <div class="panel-body">
                        <form class="form-horizontal" id="formId" role="form" method="POST" action="{{ route('login.process') }}">
                            {{ csrf_field() }}
                            <div class="inner-addon left-addon form-group{{ $errors->has('Username') ? ' has-error' : '' }}">
                                <div class="col-md-12">
                                    <i class="glyphicon glyphicon-user"></i>
                                    <input id="username" type="text" class="form-control" name="NPK" required autofocus placeholder="NPK{{--, Username--}}">
                                </div>
                            </div>
                            <div class="inner-addon left-addon form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <div class="col-md-12">
                                    <i class="glyphicon glyphicon-lock"></i>
                                    <input id="password" type="password" class="form-control" name="password" required placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="submit" id="login" class="btn btn-default btn-yellow btn-full login-button">
                                        Login
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('assets/js/jquery-2.2.4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script>
    $(function(){
        $('#login').one('click', function() {  
            $(this).attr('disabled','disabled');
            $('#formId').submit();
        });
    });
</script>
</body>
</html>