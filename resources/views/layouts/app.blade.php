@inject('posabbreviation', '\App\Domain\Karyawan\Services\PositionAbbreviation')
@extends('vendor.loader.loader',['phase'=>'Memuat laman ...'])
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Key Performance Indicator | Pupuk Kaltim</title>
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/override.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dataTables.bootstrap.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2-bootstrap.css') }}"/>
    <link href="{{ asset('assets/js/easyautocomplete/easy-autocomplete.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">
    <style>
        .img-logo{
            height: 180%;
        }
        .high{
            margin: -4% 0;
        }
        thead{
            background-color: rgb(51, 122, 183);
            color: white;
        }
        .panel-create select.form-control[disabled] {
            background: #EEEEEE url({{asset('assets/img/arrow-down.png')}}) no-repeat right 10px center;
            background-size: 13px;
            -webkit-appearance: none;
        }
        .form-group input[type=radio]:checked:disabled ~ .check::before{
            background: #f7e199;
        }
        .form-group input[type=radio]:disabled ~ .check{
            border: 5px solid #dce6ec;
        }

        div.dt-buttons{
            float: right;
            margin-left: 10px;
            margin-right: 10px; 
        }

        .text-blue {
            color: #4cc0c6;
        }

        .bg-red {
            background-color: #ff7575cc !important;
        }

        .modal-xl {
            width: 1200px;
        }
    </style>
</head>

<body oncontextmenu="return false">
@include('vendor.notifications.navbar')
    <nav class="navbar navbar-default main-menu">
        <div class="container">
            <div class="navbar-header">
                <div class="notification-mobile visible-xs">
                    @yield('notifikasi')
                </div>
                <div class="profile-mobile visible-xs">
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Hi, {{ auth()->user()->username }}
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu menu-profil" aria-labelledby="dropdownMenuProfile">
                            <li><a href="{{ route('profile') }}"><i class="fa fa-user"></i> Profil Saya</a></li>
                            <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand hidden-xs" href="{{ url('/') }}">
                    <img alt="eKPI Pupuk Kaltim Logo" src="{{ asset('assets/img/ekpi-logo.png') }}" class="responsive-image img-logo">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                @include('layouts.mainmenu')

                <ul class="nav navbar-nav navbar-right nav-ul hidden-xs">
                    <li>
                        @include('layouts.privileges')
                    </li>
                    <li class="dropdown">
                        @yield('notifikasi')
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle dropdown-for-profile" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('assets/img/user.png') }}" alt="User Profil" class="img-circle hidden-xs img-profil"> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu menu-profil">
                            <li class="profile-in-dropdown">
                                Hi, {{ auth()->user()->NPK }}
                            </li>
                            <li class="{{ checkActiveMenu('faq*') }}"><a href="{{ route('faq') }}"><i class="fa fa-edit"></i> F.A.Q</a></li>
                            <li><a href="{{ route('profile') }}"><i class="fa fa-user"></i> Profil Saya</a></li>
                            <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    {{-- @yield('submenu') --}}
    <div class="container visible-xs">
        <div class="row">
            <div class="col-sm-12">
                @include('layouts.privileges')
            </div>
        </div>
    </div>

    @yield('content')
    <div class="container-fluid footer margin-top-30">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                &copy; 2018 eKPI Pupuk Kaltim
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('assets/js/jquery-2.2.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/datepicker/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js "></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
    <script type="text/javascript">
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
    <script type="text/javascript">
        $(window).ready(function () {
            $('.loader-container').fadeOut('slow');
        });
        if ($('.main-menu .nav-ul li.active ul.dropdown-submenu').css('display') == 'block') {
            $("nav.navbar.navbar-default.main-menu").css({
                "margin-bottom": "90px"
            });
        }
        $(function () {
                $('.datetimepicker1').datetimepicker({
                format: 'YYYY/MM/DD'
            });
            });
        $( "textarea" ).addClass( "form-control" );
        $(function(){
            $('#submit').one('click', function() {
                $(this).attr('disabled','disabled');
                $('#form').submit();
            });
        });

        // $("body").delegate(".main-menu", "click", function(){ 
            
        //     $(this).closest("li").addClass("active");
        // })
    </script>
    @yield('customjs')
    @yield('notifscript')
</body>
</html>
