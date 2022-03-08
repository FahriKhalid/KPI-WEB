@inject('targetparser', 'Pkt\Domain\Rencana\Services\TargetParserService')

@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.rencana')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 margin-top-15">
                <ul class="kpi-menu">
                    <li class="active">
                        <a href="{{ route('backends.kpi.rencana.individu.show', ['id' => $data['header']->ID]) }}">Rencana KPI</a>
                    </li>
                    @if($data['karyawan']->isUnitKerja())
                        <li>
                            <a href="{{ route('backends.kpi.rencana.individu.unitkerja.show', ['id' => $data['header']->ID]) }}">KPI Unit Kerja</a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('backends.kpi.rencana.individu.penurunan.show', ['id' => $data['header']->ID]) }}">Penurunan KPI</a>
                    </li>
                    <li>
                        <a href="{{ route('backends.kpi.rencana.individu.document.show', ['id' => $data['header']->ID]) }}">Dokumen</a>
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
                            <div class="panel-title-box no-border">Data Detail Rencana KPI <span class="label label-info pull-right">{{ strtoupper($data['header']->statusdokumen->StatusDokumen) }}</span></div>
                        </div>
                        @include('backends.kpi.rencana.sharedlayouts.metadataheaderdetail')
                    </div>
                </div>
                <div class="panel panel-default panel-box panel-create">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box no-border">Data Detail Rencana KPI <span class="label label-info pull-right">{{ strtoupper($data['header']->statusdokumen->StatusDokumen) }}</span></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="alert alert-success alert-important">
                                Total Item KPI <span class="badge pull-right">{{ $data['alldetail']->count() }}</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="alert alert-success alert-important">
                                Total Bobot KPI <span class="badge pull-right">{{ $data['alldetail']->sum('Bobot') }}%</span>
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-2">
                            <div class="custom-button-container text-right">
                                <a target="Laporan Rencana KPI Individu" href="{{ route('backends.kpi.rencana.individu.print', ['id' => $data['header']->ID]) }}">
                                    <button class="btn btn-link" {{$data['header']->IDStatusDokumen != 4 ? 'disabled':''}}>
                                        <img src="{{ asset('assets/img/ic_print.png') }}"> Print
                                    </button>
                                </a>
                            </div>
                        </div>

                        @include('backends.kpi.rencana.sharedlayouts.detailrencanatable')

                        <div class="text-center margin-top-15">
                            <a href="{{ route('backends.kpi.rencana.individu') }}" class="btn btn-default">Kembali</a>
                            @if(auth()->user()->UserRole->Role === 'Karyawan' && $data['header']->IDStatusDokumen === 1)
                                <a href="{{ route('backends.kpi.rencana.individu.editdetail', ['id' => $data['header']->ID]) }}" class="btn btn-warning">Revisi Target</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        $('#detailrencana-table').DataTable({
            columnDefs: [{targets: [0], orderable: false}],
            order: [],
            sDom: 'f',
            pageLength: 40,
            fnRowCallback: function (nRow, aData, iDisplayIndex) {
                var index = iDisplayIndex +1;
                $('td:eq(0)',nRow).html(index);
                return nRow;
            }
        });
    </script>
@endsection