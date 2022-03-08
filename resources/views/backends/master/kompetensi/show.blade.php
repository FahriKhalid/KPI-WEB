@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Detail Data Kompetensi</div>
                            <div class="row">
                                <div class="col-sm-8">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>ID</strong> <span class="pull-right">{{ (! empty($data->ID)) ? $data->ID : '-' }}</span>
                                        <li class="list-group-item"><strong>PositionID</strong> <span class="pull-right">{{ $data->PositionID }}</span></li>
                                        </li><li class="list-group-item"><strong>Keterangan</strong> <span class="pull-right">{{ (! empty($data->Keterangan)) ? $data->Keterangan : '-' }}</span></li>
                                        <li class="list-group-item"><strong>Dibuat Oleh</strong> <span class="pull-right">{{ $data->createdby->username }}</span></li>
                                        <li class="list-group-item"><strong>Dibuat Pada</strong> <span class="pull-right">{{ $data->CreatedOn }}</span></li>
                                        <li class="list-group-item"><strong>Diperbarui Oleh</strong> <span class="pull-right">{{ (! empty($data->updatedby->username)) ? $data->updatedby->username : '-' }}</span></li>
                                        <li class="list-group-item"><strong>Diperbarui Pada</strong> <span class="pull-right">{{ (! empty($data->UpdatedOn)) ? $data->UpdatedOn : '-' }}</span></li>
                                        </ul>
                                </div>
                                <div class="col-sm-4">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>PositionID</strong></li>
                                        <li class="list-group-item">{!! $data->PositionID !!}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="text-center">
                                    <a href="{{ route('backend.master.kompetensi') }}" type="button" class="btn btn-default btn-orange">Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
