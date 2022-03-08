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
                    <li>
                        <a href="{{ route('backends.kpi.rencana.individu.detailbawahantaklangsung', ['id' => $data['header']->ID]) }}">Rencana KPI</a>
                    </li>
                    @if($data['karyawan']->isUnitKerja())
                        <li>
                            <a href="{{ route('backends.kpi.rencana.individu.unitkerjabawahantaklangsung', ['id' => $data['header']->ID]) }}">Rencana KPI Unit Kerja</a>
                        </li>
                    @endif
                    <li class="active">
                        <a href="{{ route('backends.kpi.rencana.individu.penurunanbawahantaklangsung', ['id' => $data['header']->ID]) }}">Penurunan KPI</a>
                    </li>
                    <li>
                        <a href="{{ route('backends.kpi.rencana.individu.documentbawahantaklangsung', ['id' => $data['header']->ID]) }}">Dokumen</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @include('backends.kpi.rencana.sharedlayouts.contentdertailpenurunan')

@endsection

@section('customjs')
    <script>
        $('#cascading-table').DataTable({
            columnDefs: [{targets: [0], orderable: false}],
            order: [],
            sDom: 'f',
            fnRowCallback: function (nRow, aData, iDisplayIndex) {
                var index = iDisplayIndex +1;
                $('td:eq(0)',nRow).html(index);
                return nRow;
            }
        });
    </script>
@endsection
