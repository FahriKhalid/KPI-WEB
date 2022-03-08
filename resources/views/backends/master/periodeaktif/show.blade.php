@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Detail Data Periode Aktif</div>
                            <div class="row">
                                <div class="col-sm-8">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>ID</strong> <span class="pull-right">{{ (! empty($data['head']->ID)) ? $data['head']->ID : '-' }}</span>
                                        <li class="list-group-item"><strong>NamaPeriode</strong> <span class="pull-right">{{ $data['head']->NamaPeriode }}</span></li>
                                        <li class="list-group-item"><strong>Tahun</strong> <span class="pull-right">{{ $data['head']->Tahun }}</span></li>
                                        <li class="list-group-item"><strong>StatusPeriode</strong> <span class="pull-right">{{ $data['head']->StatusPeriode }}</span></li>
                                        <li class="list-group-item"><strong>Aktif</strong> <span class="pull-right">{{ $data['head']->Aktif }}</span></li>
                                        </li><li class="list-group-item"><strong>Keterangan</strong> <span class="pull-right">{{ (! empty($data['head']->Keterangan)) ? $data['head']->Keterangan : '-' }}</span></li>
                                        <li class="list-group-item"><strong>Dibuat Oleh</strong> <span class="pull-right">{{ $data['head']->createdby->username }}</span></li>
                                        <li class="list-group-item"><strong>Dibuat Pada</strong> <span class="pull-right">{{ $data['head']->CreatedOn }}</span></li>
                                        <li class="list-group-item"><strong>Diperbarui Oleh</strong> <span class="pull-right">{{ (! empty($data['head']->updatedby->username)) ? $data['head']->updatedby->username : '-' }}</span></li>
                                        <li class="list-group-item"><strong>Diperbarui Pada</strong> <span class="pull-right">{{ (! empty($data['head']->UpdatedOn)) ? $data['head']->UpdatedOn : '-' }}</span></li>
                                        </ul>
                                </div>
                                <div class="col-sm-4">
                                    <ul class="list-group">
                                        <li class="list-group-item" style="background-color: rgb(51, 122, 183); color: white;"><strong>NamaPeriode</strong></li>
                                        <li class="list-group-item">{!! $data['head']->NamaPeriode !!}</li>
                                    </ul>
                                </div>
                                @foreach($data['detail'] as $detail)
                                <div class="col-sm-6">
                                    <ul class="list-group">
                                        <li class="list-group-item" style="background-color: rgb(51, 122, 183); color: white;"><strong>{{$detail->jenisperiode->NamaPeriodeKPI}}</strong></li>
                                        <li class="list-group-item">
                                            <ul class="list-group">
                                                <li class="list-group-item"><strong>Start Date</strong><span class="pull-right">{{ (! empty($detail->StartDate)) ? $detail->StartDate : '-' }}</span></li>
                                                <li class="list-group-item"><strong>End Date</strong><span class="pull-right">{{ (! empty($detail->EndDate)) ? $detail->EndDate : '-' }}</span></li>
                                                <li class="list-group-item"><strong>Keterangan</strong><span class="pull-right">{{ (! empty($detail->Keterangan)) ? $detail->Keterangan : '-' }}</span></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                @endforeach
                            </div>
                            <div class="row">
                                <div class="text-center">
                                    <a href="{{ route('backend.master.periodeaktif') }}" type="button" class="btn btn-default btn-orange">Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
