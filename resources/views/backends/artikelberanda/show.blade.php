@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Detail Narasi Home{{-- - {!! $data->Title !!}--}}</div>
                            <div class="row">
                                <div class="col-sm-8">
                                    <ul class="list-group">
                                        {{--<li class="list-group-item"><strong>Judul</strong> <span class="pull-right">{!!  $data->Title  !!}</span></li>--}}
                                        <li class="list-group-item"><strong>Konten</strong> <br> <p>{!! $data->Content !!}</p></li>
                                        <li class="list-group-item"><strong>Status</strong> <span class="pull-right">{{ $data->isActive()?'Aktif':'Non-aktif' }}</span></li>
                                        <li class="list-group-item"><strong>Dibuat Oleh</strong> <span class="pull-right">{{ $data->createdby->NamaKaryawan }}</span></li>
                                        <li class="list-group-item"><strong>Dibuat Pada</strong> <span class="pull-right">{{ $data->CreatedOn }}</span></li>
                                        <li class="list-group-item"><strong>Diperbarui Oleh</strong> <span class="pull-right">{{ (! empty($data->updatedby->NamaKaryawan)) ? $data->updatedby->NamaKaryawan : '-' }}</span></li>
                                        <li class="list-group-item"><strong>Diperbarui Pada</strong> <span class="pull-right">{{ (! empty($data->UpdatedOn)) ? $data->UpdatedOn : '-' }}</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="text-center">
                                    <a href="{{ route('artikel') }}" type="button" class="btn btn-default btn-orange">Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
