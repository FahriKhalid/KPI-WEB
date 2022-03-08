@extends('layouts.app')

@section('content')
	<div class="container margin-bottom-15">
		<div class="row">
			<div class="col-md-6 col-sm-6 info-text-hi">
				<h4 class="blue-color">Selamat Datang di KPI Online, {{ title_case(auth()->user()->Karyawan->NamaKaryawan) }}</h4>
			</div>
			<div class="col-md-6 col-sm-6 info-text-hi text-right">
				<div class="date realtime-date"></div>
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
						
						<div class="row margin-bottom-15">
							<div class="col-sm-6">
								<div class="footer-1">
									{{ $data['pendingConfirmedCount'] }} Dokumen Rencana KPI Menunggu Dikonfirmasi
								</div>
							</div>
							<div class="col-sm-6 text-right footer-2">
								<a href="{{ route('backends.kpi.rencana.individu.bawahanlangsung') }}">
									<button class="btn btn-default btn-yellow">
										Lihat Detail
									</button>
								</a>
							</div>
						</div>
						<div class="row margin-bottom-15">
							<div class="col-sm-6">
								<div class="footer-1">
									{{ $data['pendingApprovedCount'] }} Dokumen Rencana KPI Menunggu Approval
								</div>
							</div>
							<div class="col-sm-6 text-right footer-2">
								<a href="{{ route('backends.kpi.rencana.individu.bawahantaklangsung') }}">
									<button class="btn btn-default btn-yellow">
										Lihat Detail
									</button>
								</a>
							</div>
						</div>
						<div class="row margin-bottom-15">
							<div class="col-sm-6">
								<div class="footer-1">
									{{ $data['pendingConfirmedRealisasiCount'] }} Dokumen Realisasi KPI Menunggu Dikonfirmasi
								</div>
							</div>
							<div class="col-sm-6 text-right footer-2">
								<a href="{{ route('backends.kpi.realisasi.individu.bawahanlangsung') }}">
									<button class="btn btn-default btn-yellow">
										Lihat Detail
									</button>
								</a>
							</div>
						</div>
						<div class="row margin-bottom-15">
							<div class="col-sm-6">
								<div class="footer-1">
									{{ $data['pendingApprovedRealisasiCount'] }} Dokumen Realisasi KPI Menunggu Approval
								</div>
							</div>
							<div class="col-sm-6 text-right footer-2">
								<a href="{{ route('backends.kpi.realisasi.individu.bawahantaklangsung') }}">
									<button class="btn btn-default btn-yellow">
										Lihat Detail
									</button>
								</a>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	@if(isset($data['chartSummaryKPIBawahan']) && $data['chartSummaryKPIBawahan'])
		@include('backends.dashboard.partials.summarykpibawahan')
	@endif

	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<h5 class="info-title-in-dashboard">Info KPI Online</h5>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12 text-right view-more">
						<a href="javascript:void(0)" onclick="selebihnya()">
							Lihat Lebih
						</a>
					</div>
				</div>
			    <div class="divider"></div>
			    <div class="row">
			    	<?php $i=0; ?>
			    	@forelse($data['infoKPI'] as $item)
					<div class="col-sm-3 col-xs-6">
				    	<div class="item-listing" id="{{$i}}" style="height: 300px;">
			    			<a href="{{ route('dashboard.infokpi.detail', $item->ID) }}">
			    				@if(isset($item->Gambar))
				    				<img src="{{ route('image.resize', ['modulename' => 'info', 'width' => 960, 'height' => 637, 'imagename' => $item->Gambar]) }}" class="responsive-image">
				    			@else
				    				<img src="{{ route('image.resize', ['modulename' => 'dummy', 'width' => 960, 'height' => 637, 'imagename' => 'dummy.png']) }}" class="responsive-image">
				    			@endif
								<div class="description-listing">
									<h4>{{substr(strip_tags($item->Judul,''),0,25) }} {{strlen($item->Judul)>25? '...': ''}}</h4>
									<div class="date">
										{{ date('d M Y', strtotime($item->Tanggal_publish)) }} s/d {{ (! is_null($item->Tanggal_berakhir)) ? date('d M Y', strtotime($item->Tanggal_berakhir)) : '-' }}
									</div>
								</div>
			    			</a>
				    	</div>
				    	<?php $i+=1; ?>
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
        for(var x = 0; x < {{$i}}; x++ ){
        	if(x >= 4){
        		$("#"+x).hide();
        	}
        }

        function selebihnya(){
        	for(var x = 0; x < {{$i}}; x++ ){
	        	if(x >= 4){
	        		$("#"+x).toggle();
	        	}
	        }
        }
    </script>
    <script type="text/javascript">
        var datetime_moment = null,
                date_moment = null;

        var update = function () {
            date_moment = moment(new Date());
            datetime_moment.html(date_moment.format('DD MMMM YYYY, h:mm:ss a'));
        };

        $(document).ready(function(){
            datetime_moment = $('.realtime-date');
            update();
            setInterval(update, 1000);
        });

    </script>

	@if(isset($data['chartSummaryKPIBawahan']) && $data['chartSummaryKPIBawahan'])
		@include('backends.dashboard.partials.chartsummarykpibawahanscript')
	@endif

@endsection