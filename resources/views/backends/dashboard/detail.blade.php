@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-11" style="margin-right: 2%; margin-left: 2%">
                            <div class="row">
                            	<div class="text-center">
                            		<img src="{{ route('image.resize', ['modulename' => 'info', 'width' => 600, 'height' => 350, 'imagename' => (! is_null($data->Gambar)) ? $data->Gambar : 'dummy.png']) }}" align="center">
                            	</div>
                            	<h1>{{ $data->Judul }}</h1>
                                	
                            </div>
                            <div class="row">
                            	{!! $data->Informasi !!}
                            </div>
                            <div class="footer">
                            	{{ $data->Tanggal_publish }}
								<button class="close" style="float: right;">
                                    <a href="{{ route('dashboard') }}"> Kembali
                                    </a>
								</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
