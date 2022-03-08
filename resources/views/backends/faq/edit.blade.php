<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Key Performance Indicator | Pupuk Kaltim</title>
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400" rel="stylesheet">
    <script type="text/javascript" src="{{ asset('assets/js/jquery-2.2.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
</head>
<body>
<div class="landing-home-parallax faq-landing">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-3 logo-in-faq">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/img/logo_inlanding.png') }}" class="responsive-image">
                </a>
            </div>
            <div class="col-md-6 col-sm-6 menu-in-faq text-center">
                <ul class="menu-faq">
                    <li>
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('faq') }}">FAQ</a>
                    </li>
                </ul>
            </div>
            @if(!\Auth::guard('web')->check())
                <div class="col-md-3 col-sm-3 login-in-faq text-right">
                    <a href="{{ route('login') }}" class="text-white">
                        Login
                    </a>
                </div>
            @else
                <ul class="nav navbar-nav navbar-right nav-ul text-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle dropdown-for-profile" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('assets/img/user.png') }}" alt="User Profil" class="img-circle hidden-xs img-profil"> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu menu-profil">
                            <li class="profile-in-dropdown">
                                Hi, {{ auth()->user()->NPK }}
                            </li>
                            <li><a href="{{ route('profile') }}"><i class="fa fa-user"></i> Profil Saya</a></li>
                            <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            @endif
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 description-in-faq text-white text-center">
                <h3>
                    Key Performance Indicator
                    <br>(KPI) - Online
                </h3>
            </div>
        </div>
    </div>
</div>
<div class="container container-faq-item">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <h3 class="faq-title">FAQ</h3>
            @include('vendor.flash.message')
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-default panel-faq item-faq-main">
                <div class="panel-body">
                    <h4 class="item-title-faq text-center">Edit FAQ</h4>
                        <form class="form-horizontal" method="post" action="{{route('faq.update',['id'=> $data['ID']])}}">
                            {{csrf_field()}}
                            <input type="hidden" name="_method" value="put">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Pertanyaan</label>
                                <div class="col-sm-5">
                                    <input type="text" name="Question"  class="form-control" placeholder="Pertanyaan" value="{{$data['Question']}}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Jawaban</label>
                                <div class="col-sm-7">
                                    <textarea rows="10" name="Answer">{!! $data['Answer'] !!}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Aktif</label>
                                <div class="col-sm-4">
                                    <ul class="container-check">
                                        <li>
                                            <input type="radio" id="s-option" name="Aktif" value="0" {{$data['Aktif']==false?'checked':''}}>
                                            <label for="s-option">Tidak</label>
                                            <div class="check"><div class="inside"></div></div>
                                        </li>
                                        <li>
                                            <input type="radio" id="f-option" name="Aktif" value="1" {{$data['Aktif']==true?'checked':''}}>
                                            <label for="f-option">Ya</label>
                                            <div class="check"></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-7 save-container">
                                    <button type="submit" class="btn btn-default btn-blue">Kirim</button>
                                    <a href="{{ route('faq') }}" class="btn btn-default btn-orange">Batal</a>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid footer">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            &copy; {{ date('Y') }} KPI Online Pupuk Kaltim
        </div>
    </div>
</div>
@include('vendor.loader.loader',['phase'=>'Menyiapkan ...'])
<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=h15ws19lzjua0cwwlldwgyhfy3bra8xhir36p9rtgxhkadn6"></script>
<script>
    tinymce.init({ selector:'textarea' });
    $(window).ready(function () {
        $('.loader-container').fadeOut('slow');
    });
</script>
<style>
    .mce-edit-area{
        border-right: 1px solid #CCC!important;
    }
</style>
</body>
</html>
