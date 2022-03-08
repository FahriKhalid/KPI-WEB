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
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400" rel="stylesheet">
</head>
<body>
    <div class="landing-home-parallax">
    	<div class="container">
    		<div class="row">
    			<div class="col-md-6 col-sm-6 logo-in-landing">
    				<a href="{{ url('/') }}">
						<img src="{{ asset('assets/img/logo_inlanding.png') }}" class="responsive-image">
					</a>
				</div>
				<div class="col-md-6 col-sm-6 description-in-landing text-white">
					<h3>
						Key Performance Indicator
						<br>(KPI) - Online
					</h3>
					<div class="hidden-sm hidden-xs">
						@if(isset($data['artikel']))
							{{--<h4>{{$data['artikel']->Title}}</h4>--}}
							<p>
								{!!$data['artikel']->Content!!}
							</p>
						@else
							<div class="text-center" style=" min-height: 6em;">
								<h4>Konten Kosong</h4>
								<p></p>
							</div>
						@endif
					</div>
					<a class="hidden-sm hidden-xs" href="{{ route('login') }}">
						<button class="btn btn-default btn-yellow">Login</button>
					</a>
					<a class="visible-sm visible-xs" href="{{ route('login') }}">
						<button class="btn btn-full btn-default btn-yellow">Login</button>
					</a>
				</div>
    		</div>
    	</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
			    <div class="panel panel-default box-landingpage">
					<div class="panel-body">
						<div class="visible-sm visible-xs">
							@if(isset($data['artikel']))
								{{--<h4 class="text-center">{{$data['artikel']->Title}}</h4>--}}
								<p>
									{!!$data['artikel']->Content!!}
								</p>
							@else
								<div class="text-center" style=" min-height: 6em;">
									<h4>Konten Kosong</h4>
									<p></p>
								</div>
							@endif
						</div>
						<div class="col-sm-12">
							<div class="panel-title-box">{{ $data->Judul }} <span class="pull-right">{{ date('d M Y', strtotime($data->Tanggal_publish)) }}</span></div>
						</div>
						<div class="col-sm-12">
							<div class="text-center">
								@if($data->Gambar)
									<img src="{{ route('image.resize.gallery', ['modulename' => 'info', 'width' => 500, 'imagename' => $data->Gambar]) }}" class="responsive-image img-thumbnail">
								@endif
							</div>
						</div>
						<div class="col-sm-12 margin-top-30">
							<div class="well well-lg ">
								{!! $data->Informasi !!}
							</div>
						</div>
						<div class="text-center">
							<a href="{{ route('home') }}" class="btn btn-default btn-blue">Kembali</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12 text-center">
				<ul class="footer-menu">
					<li><a href="{{ route('faq') }}">FAQ</a></li>
					<li><a href="#">Kontak</a></li>
				</ul>
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
    <script type="text/javascript" src="{{ asset('assets/js/jquery-2.2.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script type="text/javascript">
    	$(document).ready(function() {
          	$('.owl-carousel').owlCarousel({
	            loop: false,
	            center: false,
            	margin: 10,
            	responsiveClass: true,
            	navText: ["&#139;","&#155;"],
            	responsive: {
              		0: {
                		items: 1,
                		nav: true
              		},
              		600: {
                		items: 3,
                		nav: false
              		},
              		1000: {
                		items: 3,
                		nav: true,
						margin: 20
              		}
            	}
          	})
        })
    </script>
</body>
</html>
