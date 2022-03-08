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
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dataTables.bootstrap.min.css') }}"/>
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-default main-menu">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img alt="Key Performance Indicator Logo" src="{{ asset('assets/img/logo_inlanding.png') }}" class="responsive-image img-logo">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-left nav-ul">
                    <li class="active"><a href="#">Dashboard</a></li>
                    <li><a href="#">Rencana KPI</a></li>
                    <li><a href="#">Realisasi KPI</a></li>
                    <li><a href="#">Master</a></li>
                    <li><a href="#">Kamus KPI</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right nav-ul">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bell"></i> 
                            <span class="notif-count">10</span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu notification">
                            <section class="panel bg-white">
                                <header class="panel-heading" id="unread"> 
                                    <strong>
                                        <span class="count-n notifCount">0</span> Notifikasi belum dibaca
                                    </strong>
                                </header>
                                <div class="list-group">
                                    <a href="#" class="list-group-item">
                                        <span class="badge">New</span> Dapibus ac facilisis in
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <span class="badge">New</span> Dapibus ac facilisis in
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <span class="badge">New</span> Dapibus ac facilisis in
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <span class="badge">New</span> Dapibus ac facilisis in
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <span class="badge">New</span> Dapibus ac facilisis in
                                    </a>
                                </div>
                            </section>
                        </ul>
                    </li>
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hi Admin <span class="caret"></span></a>
                        <ul class="dropdown-menu menu-profil">
                            <li><a href="#"><i class="fa fa-user"></i> Profil Saya</a></li>
                            <li><a href="#"><i class="fa fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <img src="{{ asset('assets/img/user.png') }}" alt="User Profil" class="img-circle hidden-xs img-profil">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @yield('submenu')
    
    @yield('content')
    <div class="container-fluid footer margin-top-30">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                &copy; {{ date('Y') }} KPI Online Pupuk Kaltim
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('assets/js/jquery-2.2.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
    @yield('customjs')
</body>
</html>
