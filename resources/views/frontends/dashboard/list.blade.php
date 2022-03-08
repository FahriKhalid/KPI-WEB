@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<h5 class="info-title-in-dashboard">Info KPI Online</h5>
					</div>
				</div>
			    <div class="divider"></div>
			    <div class="row">
					@forelse($data['infoKPI'] as $item)
					<div class="col-sm-3 col-xs-6">
				    	<div class="item-listing">
			    			<a href="{{ route('dashboard.infokpi.detail', $item->ID) }}">
				    			<img src="{{ route('image.resize', ['modulename' => 'info', 'width' => 960, 'height' => 637, 'imagename' => $item->Gambar]) }}" class="responsive-image">
								<div class="description-listing">
									<h4>{{ $item->Judul }}</h4>
									<div class="date">
										{{ date('d M Y', strtotime($item->Tanggal_publish)) }}
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
	
@endsection