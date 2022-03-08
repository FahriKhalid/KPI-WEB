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
	<style>
		.nav>li>a:focus, .nav>li>a:hover {
			text-decoration: none;
			 background-color:transparent;
		}
		.nav .open>a, .nav .open>a:focus, .nav .open>a:hover {
			background-color: transparent;
			border-color:transparent;
		}
	</style>
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
				@include('vendor.flash.message')
			</div>
			@if(\Auth::guard('web')->check())
				@if(\Auth::user()->UserRole->Role === 'Administrator')
				<div class="col-md-6 col-sm-6 col-xs-6">
					<h3 class="faq-title">FAQ</h3>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6 text-right">
					<a class="btn btn-default btn-blue margin-top-15" href="{{route('faq.create')}}" >Buat FAQ Baru</a>
				</div>
				@else
					<div class="col-md-12 col-sm-12 col-xs-12">
						<h3 class="faq-title">FAQ</h3>
					</div>
					@endif
			@endif
		</div>
		<div class="row">
			@if(isset($data['faq'][0]))
			@if(\Auth::guard('web')->check())
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="panel panel-default panel-faq item-faq-main">
						<div class="panel-body">
							<h4 class="item-title-faq text-center">Selamat datang, {{$data['user']->karyawan->NamaKaryawan}} di laman <i>Frequently Asked Question - KPI Online</i> !</h4>
							<div class="panel-group" id="accordion-auth" role="tablist" aria-multiselectable="true">
							@foreach($data['faq'] as $key=>$faq)
								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="heading{{$key}}">
										<a role="button" data-toggle="collapse" data-parent="#accordion-auth" href="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
											<i class="more-less fa fa-angle-right"></i>
											<div class="panel-title row">
												<div class="col-md-9 col-xs-9">{!! \auth()->user()->UserRole->Role === 'Administrator'?$faq->isActive()?'<i class="fa fa-check" style="color:green;"></i>':'<i class="fa fa-times" style="color:red;"></i>':'' !!} <b> {{ $faq->Question}}</b></div>
												<div class="col-md-2 col-xs-2 text-right">@include('backends.faq.actionbuttons')</div>
											</div>
										</a>
									</div>
									<div id="collapse{{$key}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$key}}">
										<div class="panel-body">
											{!! empty($faq->Answer)?'<div class="empty-info text-center"><h3 class="no-info-text">Tidak Ada Jawaban</h3></div>': $faq->Answer!!}
										</div>
									</div>
								</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			@else
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="panel panel-default panel-faq item-faq-main">
						<div class="panel-body">
							<h4 class="item-title-faq text-center"><i>Frequently Asked Question - KPI Online</i></h4>
							<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								@foreach($data['faq'] as $key=>$faq)
									<div class="panel panel-default">
										<div class="panel-heading" role="tab" id="heading{{$key}}">
											<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
												<i class="more-less fa fa-angle-right"></i>
												<h4 class="panel-title">
													<b>{{$faq->Question}}</b>
												</h4>
											</a>
										</div>
										<div id="collapse{{$key}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$key}}">
											<div class="panel-body">
												{!! empty($faq->Answer)?'<div class="empty-info text-center"><h3 class="no-info-text">Tidak Ada Jawaban</h3></div>': $faq->Answer!!}
											</div>
										</div>
									</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			@endif
			@else
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="panel panel-default panel-faq item-faq-main">
						<div class="panel-body">
							<h4 class="item-title-faq text-center"><i>Frequently Asked Question - KPI Online</i></h4>
							<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								<div class="panel panel-default">
									<div class="panel-heading">
										<div class="empty-info text-center">
											<img src="{{ asset('assets/img/ic_info_empty.png') }}" class="responsive-image">
											<h3 class="no-info-text">FAQ Kosong</h3>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif
		</div>
	</div>
	@if(\Auth::guard('web')->check())
		@if(\Auth::user()->UserRole->Role === 'Administrator')
			<div class="modal fade modal-notification" id="faqDeleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title" id="myModalLabel">Hapus data FAQ?</h4>
						</div>
						<div class="modal-body">
							Peringatan! Data yang dihapus tidak akan bisa dikembalikan.
						</div>
						<div class="modal-footer">
							<button type="button" id="modal-confirm-delete-button" data-url="" class="btn btn-default btn-danger delete">Ya</button>
							<button type="button" class="btn btn-default btn-no" data-dismiss="modal">Tidak</button>
						</div>
					</div>
				</div>
			</div>
			<script type="text/javascript">
				$('#modal-confirm-delete-button').click(function()
				{
					var url = $(this).data('url');
					var token = '{{ csrf_token() }}';
					var data = {_method: 'delete', _token: token};
					$.ajax({
						url: url,
						type: 'post',
						data: data,
						success: function(result) {
							document.location.reload(true);
						},
						error: function(xhr) {
							alert('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText);
						}
					});
				});
			</script>
		@endif
	@endif
	<div class="container-fluid footer">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12 text-center">
				&copy; {{ date('Y') }} KPI Online Pupuk Kaltim
			</div>
		</div>
	</div>
    <script type="text/javascript">
    	function toggleIcon(e) {
		    $(e.target)
		        .prev('.panel-heading')
		        .find(".more-less")
		        .toggleClass('fa-angle-right fa-angle-down');
		}
		$('.panel-group').on('hidden.bs.collapse', toggleIcon);
		$('.panel-group').on('shown.bs.collapse', toggleIcon);
    </script>
</body>
</html>
