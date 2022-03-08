@inject('targetparser', 'Pkt\Domain\Rencana\Services\TargetParserService')
@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.realisasi')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 margin-top-15">
                <ul class="kpi-menu">
                    <li class="active">
                        <a href="{{ route('backends.kpi.realisasi.individu.show', ['id' => $data['header']->ID]) }}">Realisasi KPI</a>
                    </li>
                    <li>
                        <a href="{{ route('backends.kpi.realisasi.individu.indexdocument', ['id' => $data['header']->ID]) }}">Dokumen</a>
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
                            <div class="panel-title-box no-border">Data Detail Realisasi KPI <span class="label label-info pull-right">{{ strtoupper($data['header']->statusdokumen->StatusDokumen) }}</span></div>
                        </div>
                        @include('backends.kpi.realisasi.sharedlayouts.metadataheader')
                    </div>
                </div>
                <div class="panel panel-default panel-box panel-create">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box no-border no-margin-bottom">Detail Realisasi KPI {{ $data['periode']->NamaPeriode }}</div>
                        </div>
                        @if($data['header']->IDStatusDokumen != 4)
                            <div class="col-sm-10">
                                <div class="alert alert-warning alert-important">
                                    Cetak laporan KPI hanya berlaku pada realisasi yang sudah diapprove
                                </div>
                            </div>
                        @endif
                        <div class="{{$data['header']->IDStatusDokumen == 4?'col-sm-offset-10':''}} col-sm-2">
                            <div class="custom-button-container text-right">
                                <a  target="Laporan Realisasi KPI Individu" href="{{ route('backends.kpi.realisasi.individu.print', ['id' => $data['header']->ID]) }}">
                                    <button class="btn btn-link" {{$data['header']->IDStatusDokumen != 4 ?'disabled':''}}>
                                        <img src="{{ asset('assets/img/ic_print.png') }}"> Print
                                    </button>
                                </a>
                            </div>
                        </div>
                        @include('backends.kpi.realisasi.sharedlayouts.detailrealisasitable')
                        <div class="text-center margin-top-15">
                            <a href="{{ route('backends.kpi.realisasi.individu') }}" class="btn btn-default">Kembali</a>
                            @if(auth()->user()->UserRole->Role === 'Karyawan')
                                @if($data['header']->isDraft())
                                <a href="{{ route('backends.kpi.realisasi.individu.editdetail', ['id' => $data['header']->ID]) }}" class="btn btn-warning">Revisi Realisasi</a>
                                @endif
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
        $(function() {
            $('#detailrealisasi-table').DataTable({
                columnDefs: [{targets: [0], orderable: false}],
                pageLength: 100,
                order: [],
                sDom: 'f',
                fnRowCallback: function (nRow, aData, iDisplayIndex) {
                    var index = iDisplayIndex +1;
                    $('td:eq(0)',nRow).html(index);
                    return nRow;
                }
            });
        });
    </script>
@endsection