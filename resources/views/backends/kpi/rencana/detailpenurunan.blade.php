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
                    <ul class="kpi-menu">
                        <li>
                            <a href="{{ route('backends.kpi.rencana.individu.show', ['id' => $data['header']->ID]) }}">Rencana KPI</a>
                        </li>
                        @if($data['karyawan']->isUnitKerja())
                            <li>
                                <a href="{{ route('backends.kpi.rencana.individu.unitkerja.show', ['id' => $data['header']->ID]) }}">KPI Unit Kerja</a>
                            </li>
                        @endif
                        <li class="active">
                            <a href="{{ route('backends.kpi.rencana.individu.penurunan.show', ['id' => $data['header']->ID]) }}">Penurunan KPI</a>
                        </li>
                        <li>
                            <a href="{{ route('backends.kpi.rencana.individu.document.show', ['id' => $data['header']->ID]) }}">Dokumen</a>
                        </li>
                    </ul>
                </ul>
            </div>
        </div>
    </div>

    @include('backends.kpi.rencana.sharedlayouts.contentdertailpenurunan')

@endsection

@section('customjs')
    <script>
        $('#cascading-table').DataTable({
            lengthMenu: [
                [5, 10, 15, 20, 100],
                ["5", "10", "15", "20", "100"]
            ],
            Dom: '<"top"l<"clear">><"bottom"p<"clear">>'
        });
    </script>
@endsection