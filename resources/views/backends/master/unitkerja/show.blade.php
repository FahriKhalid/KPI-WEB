@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Detail Data Unit Kerja</div>
                            <div class="row">
                                <div class="col-sm-8">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>Kode Unit Kerja</strong> <span class="pull-right">{{ (! empty($data->CostCenter)) ? $data->CostCenter : '-' }}</span></li><li class="list-group-item"><strong>Keterangan</strong> <span class="pull-right">{{ (! empty($data->Keterangan)) ? $data->Keterangan : '-' }}</span></li>
                                        <li class="list-group-item"><strong>Aktif</strong> <span class="pull-right">{{ $data->Aktif==true?'Aktif':'Non-Aktif' }}</span></li>
                                        {{--<li class="list-group-item"><strong>Field1</strong> <span class="pull-right">{{ $data->Field1 }}</span></li>--}}
                                        {{--<li class="list-group-item"><strong>Field2</strong> <span class="pull-right">{{ $data->Field2 }}</span></li>--}}
                                        {{--<li class="list-group-item"><strong>Field3</strong> <span class="pull-right">{{ $data->Field3 }}</span></li>--}}
                                        {{--<li class="list-group-item"><strong>Field4</strong> <span class="pull-right">{{ $data->Field4 }}</span></li>--}}
                                        {{--<li class="list-group-item"><strong>Field5</strong> <span class="pull-right">{{ $data->Field5 }}</span></li>--}}
                                        <li class="list-group-item"><strong>Dibuat Oleh</strong> <span class="pull-right">{{ $data->createdby->username }}</span></li>
                                        <li class="list-group-item"><strong>Dibuat Pada</strong> <span class="pull-right">{{ $data->CreatedOn }}</span></li>
                                        <li class="list-group-item"><strong>Diperbarui Oleh</strong> <span class="pull-right">{{ (! empty($data->updatedby->username)) ? $data->updatedby->username : '-' }}</span></li>
                                        <li class="list-group-item"><strong>Diperbarui Pada</strong> <span class="pull-right">{{ (! empty($data->UpdatedOn)) ? $data->UpdatedOn : '-' }}</span></li>
                                        </ul>
                                </div>
                                <div class="col-sm-4">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>Deskripsi</strong></li>
                                        <li class="list-group-item">{!! $data->Deskripsi !!}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="text-center">
                                    <a href="{{ route('backend.master.unitkerja') }}" type="button" class="btn btn-default btn-orange">Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
