@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Detail Data Info KPI</div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>ID</strong> <span class="pull-right">{{ (! empty($data->ID)) ? $data->ID : '-' }}</span></li>
                                        <li class="list-group-item"><strong>Judul</strong> <span class="pull-right">{{ $data->Judul }}</span></li>
                                        <li class="list-group-item"><strong>Tanggal_publish</strong> <span class="pull-right">{{ $data->Tanggal_publish }}</span></li>
                                        <li class="list-group-item"><strong>Tanggal_berakhir</strong> <span class="pull-right">{{ $data->Tanggal_berakhir }}</span></li>
                                        <li class="list-group-item"><strong>Gambar</strong> <span class="pull-right">{{ $data->Gambar }}</span></li>
                                        <!-- <li class="list-group-item"><strong>Informasi</strong> <span class="pull-right">{!! $data->Informasi !!}</span></li> -->
                                        <li class="list-group-item"><strong>User_id</strong> <span class="pull-right">{{ (! empty($data->user_id)) ? $data->user_id : '-' }}</span></li>
                                    </ul>
                                </div>
                                <div class="col-sm-12">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>Informasi</strong></li>
                                        <li class="list-group-item">{!! $data->Informasi !!}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="text-center">
                                    <a href="{{ route('backend.kpi.info') }}" type="button" class="btn btn-default btn-orange">Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
