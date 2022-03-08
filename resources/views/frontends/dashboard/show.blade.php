@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<div class="panel panel-default panel-box panel-create">
					<div class="panel-body">
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
							<a href="{{ route('dashboard') }}" class="btn btn-default btn-blue">Kembali ke Dashboard</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('customjs')
@endsection