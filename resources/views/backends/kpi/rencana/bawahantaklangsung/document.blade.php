@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.rencana')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 margin-top-15">
                <ul class="kpi-menu">
                    <li>
                        <a href="{{ route('backends.kpi.rencana.individu.detailbawahantaklangsung', ['id' => $data['header']->ID]) }}">Rencana KPI</a>
                    </li>
                    @if($data['karyawan']->isUnitKerja())
                        <li>
                            <a href="{{ route('backends.kpi.rencana.individu.unitkerjabawahantaklangsung', ['id' => $data['header']->ID]) }}">Rencana KPI Unit Kerja</a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('backends.kpi.rencana.individu.penurunanbawahantaklangsung', ['id' => $data['header']->ID]) }}">Penurunan KPI</a>
                    </li>
                    <li class="active">
                        <a href="{{ route('backends.kpi.rencana.individu.documentbawahantaklangsung', ['id' => $data['header']->ID]) }}">Dokumen</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 margin-top-30">
                <div class="panel panel-default panel-box panel-create">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="border-bottom-container margin-bottom-15">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">Data Karyawan</div>
                                    <div class="panel-body">
                                        <ul class="list-group">
                                            <li class="list-group-item"><strong>Tahun</strong><span class="pull-right">{{ $data['header']->Tahun }}</span></li>
                                            <li class="list-group-item"><strong>NPK</strong><span class="pull-right">{{ $data['karyawan']->NPK }}</span></li>
                                            <li class="list-group-item"><strong>Nama Karyawan</strong><span class="pull-right">{{ $data['karyawan']->NamaKaryawan }}</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('backends.kpi.rencana.sharedlayouts.documentrencanatable')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
