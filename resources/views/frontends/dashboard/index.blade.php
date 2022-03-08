@extends('layouts.app')

@section('content')
	<div class="container margin-bottom-15">
		<div class="row">
			<div class="col-md-6 col-sm-6 info-text-hi">
				<h4 class="blue-color">Hai Selamat Datang {{ \Auth::guard('web')->User()->UserRole->Role }}</h4>
				
				
				<p>Selamat Datang di Key Performance Indicator (KPI) - Online</p>
			</div>
			<div class="col-md-6 col-sm-6 info-text-hi text-right">
				<div class="date realtime-date"></div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-sm-6">
				<div class="panel panel-default panel-chart panel-box">
					<div class="panel-body">
						<div class="col-sm-6">
							<div class="panel-title-box">Total data</div>
						</div>
						<div class="col-sm-6 text-right">
							<div class="panel-title-box blue-color">10000</div>
						</div>
						<canvas id="canvas"></canvas>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-3 col-xs-6">
				<div class="panel panel-default panel-blue panel-box">
					<div class="panel-body">
						<div class="col-sm-12">
							<h4>Data Rencana KPI</h4>
						</div>
						<div class="col-sm-6">
							<h1>281+</h1>
						</div>
						<div class="col-sm-6">
							<img src="{{ asset('assets/img/ic_data.png') }}" class="responsive-image">
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-3 col-xs-6">
				<div class="panel panel-default panel-blue panel-box">
					<div class="panel-body">
						<div class="col-sm-12">
							<h4>Data Realisasi KPI</h4>
						</div>
						<div class="col-sm-6">
							<h1>281+</h1>
						</div>
						<div class="col-sm-6">
							<img src="{{ asset('assets/img/ic_badge.png') }}" class="responsive-image">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<div class="panel panel-default panel-waiting panel-box">
					<div class="panel-body">
						<img src="{{  asset('assets/img/ic_notification.png')}}" class="responsive-image notif-image-dashboard">
						<h3>Menunggu Persetujuan</h3>
						<p>Anda memiliki dokumen yang sedang memerlukan persetujuan</p>
					</div>
					<div class="panel-footer">
						<div class="row">
							<div class="col-sm-6">
								<div class="footer-1">
									3 Dokumen
								</div>
							</div>
							<div class="col-sm-6 text-right footer-2">
								<button class="btn btn-default btn-yellow">Lihat Detail</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<h5 class="info-title-in-dashboard">Info KPI Online</h5>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12 text-right view-more">
						<a href="#">
							Lihat Lebih
						</a>
					</div>
				</div>
			    <div class="divider"></div>
			    <div class="row">
					@forelse($data['infoKPI'] as $item)
					<div class="col-sm-3 col-xs-6">
				    	<div class="item-listing">
			    			<a href="{{ route('dashboard.show', $item->ID) }}">
			    				@if(isset($item->Gambar))
				    				<img src="{{ route('image.resize', ['modulename' => 'info', 'width' => 960, 'height' => 637, 'imagename' => $item->Gambar]) }}" class="responsive-image">
				    			@else
				    				<img src="{{ route('image.resize', ['modulename' => 'dummy', 'width' => 960, 'height' => 637, 'imagename' => 'dummy.png']) }}" class="responsive-image">
				    			@endif
								<div class="description-listing">
									<h4>{{ $item->Judul }}</h4>
									<div class="date">
										{{ date('d M Y', strtotime($item->Tanggal)) }}
									</div>
								</div>
			    			</a>
				    	</div>
					</div>
					@empty
					<div class="col-sm-12 col-xs-12">
						<div class="empty-info text-center">
							<img src="{{ asset('assets/img/ic_info_empty.png') }}" class="responsive-image">
							<h3 class="no-info-text">Tidak ada informasi</h3>
						</div>
					</div>
					@endforelse
			    </div>
			</div>
		</div>
	</div>
@endsection

@section('customjs')
	<script type="text/javascript" src="{{ asset('assets/js/Chart.bundle.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/utils.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>
	<script>
        var config = {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June"],
                datasets: [{
                    label: "Key Performance Indicator",
                    fill: false,
                    lineTension: 0,
                    backgroundColor: window.chartColors.blue,
                    borderColor: window.chartColors.blue,
                    pointBorderColor: "#f67a21",
					pointBackgroundColor: "#f67a21",
					pointHoverBackgroundColor: "#fff",
					pointHoverBorderColor: "#f67a21",
					pointBorderWidth: 0.5,
                    data: [ '0', '5', '5', '10', '10', '14',],
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:false,
                    text:'Chart.js Line Chart'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Month'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };

        window.onload = function() {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myLine = new Chart(ctx, config);
        };
    </script>
    <script type="text/javascript">
        var datetime_moment = null,
                date_moment = null;

        var update = function () {
            date_moment = moment(new Date())
            datetime_moment.html(date_moment.format('DD MMMM YYYY, h:mm:ss a'));
        };

        $(document).ready(function(){
            datetime_moment = $('.realtime-date')
            update();
            setInterval(update, 1000);
        });

    </script>
@endsection