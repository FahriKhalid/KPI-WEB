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
                        <a href="{{ route('backends.kpi.rencana.individu.show', ['id' => $data['header']->ID]) }}">Rencana KPI</a>
                    </li>
                    @if($data['karyawan']->isUnitKerja())
                        <li class="active">
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
                            <div class="panel-title-box no-border">Detail Rencana KPI Unit Kerja<span class="label label-info pull-right">{{ strtoupper($data['header']->statusdokumen->StatusDokumen) }}</span></div>
                        </div>
                        @include('backends.kpi.rencana.sharedlayouts.metadataheaderdetail')
                    </div>
                </div>
                <div class="panel panel-default panel-box panel-create">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box no-border no-margin-bottom">Item KPI</div>
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
                        <div class="col-lg-offset-2 col-sm-2 pull-right">
                            <div class="custom-button-container text-right">
                                <a target="Laporan Rencana KPI Unit Kerja" href="{{ route('backends.kpi.rencana.individu.unitkerja.print', ['id' => $data['header']->ID]) }}">
                                    <button class="btn btn-link" {{$data['header']->IDStatusDokumen != 4 ? 'disabled':''}}>
                                        <img src="{{ asset('assets/img/ic_print.png') }}"> Print
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="margin-min-15" style="overflow-x: scroll; width: calc(100% + 30px);">
                            <table id="detailrencana-table" class="table table-striped" width="2000px">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kelompok</th>
                                    <th>KRA</th>
                                    <th>KPI</th>
                                    <th>Bobot</th>
                                    <th>Satuan</th>
                                    @for($i=1; $i<=$data['target']; $i++)
                                        <th>Target {{$data['periodeTarget']}} - {{ $i }}</th>
                                    @endfor
                                    <th>Sifat</th>
                                    <th>Jenis KPI</th>
                                    <th>Sbg KPI Bawahan</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $totalBobot = 0; $no = 1;
                                @endphp
                                @forelse($data['alldetail'] as $detail)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $detail->aspekkpi->AspekKPI }}</td>
                                        <td>{{ $detail->DeskripsiKRA }}</td>
                                        <td>{{ $detail->DeskripsiKPI }}</td>
                                        <td>{{ $detail->Bobot }}%</td>
                                        <td>{{ $detail->satuan->Satuan }}</td>
                                        @for($i=1; $i<=$data['target']; $i++)
                                            <td>{{ $detail->{'Target'.$targetparser->targetCount($data['target'])[$i-1]} or '-' }}</td>
                                        @endfor
                                        <td>{{ $detail->jenisappraisal->JenisAppraisal }}</td>
                                        <td>{{ $detail->persentaserealisasi->PersentaseRealisasi }}</td>
                                        <td class="text-center">{!! $detail->IsKRABawahan ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>' !!}</td>
                                        @php
                                            $totalBobot += $detail->Bobot;
                                        @endphp
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 9 + $data['target'] }}" class="text-center">Tidak ada data detail rencana KPI</td>
                                    </tr>
                                @endforelse
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="4">Total Item KPI</th>
                                    <th>{{ $data['alldetail']->count() }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4">Total Bobot</th>
                                    <th>{{ $totalBobot }}%</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center">
                            @if(auth()->user()->IDRole == '3')
                                @if($data['header']->isDraft())
                                    <button class="btn btn-default btn-blue" id="register" data-idrencana="{{ $data['header']->ID }}"><i class="fa fa-check "></i> Register Rencana KPI</button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-notification" id="actionConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close noCallButton" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('assets/img/ic_warning.png') }}" class="img-warning-modal">
                    <h4 class="modal-title" id="actionModalTitle"></h4>
                    <p id="actionModalContent"></p>
                    <form id="modalform" class="form-horizontal">
                        {!! csrf_field() !!}
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-yellow" id="callButton">Ya</button>
                    <button type="button" class="btn btn-default btn-no noCallButton" data-dismiss="modal">Tidak</button>
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

        $('.noCallButton').click(function () {
            $('#modalform').attr('action', '');
            $('.appendedInput').remove();
        });

        $('#register').click(function() {
            var form = $('#modalform');
            var action = "{{ route('backends.kpi.rencana.individu.register') }}";
            var modalTitle = 'Register Rencana KPI Individu';
            var modalContent = 'Apakah anda yakin untuk melakukan REGISTER Rencana KPI Individu Anda?';
            form.attr('action', action);
            form.append('<input type="hidden" name="id[]" value="'+ $(this).data('idrencana') + '" class="appendedInput">');
            $('#actionModalTitle').html(modalTitle);
            $('#actionModalContent').html(modalContent);
            $('#actionConfirmModal').modal('show');
            $('#callButton').click(function(event) {
                event.preventDefault();
                $(this).attr('disabled','disabled');
                $.ajax({
                    url: action,
                    type: 'post',
                    data: form.serialize().replace(/%5B%5D/g, '[]'),
                    success: function(result) {
                        if(result.status) {
                            window.location = "{{ route('backends.kpi.rencana.individu') }}";
                        } else {
                            alert(result.errors);
                            document.location.reload(true);
                        }
                    },
                    error: function(xhr) {
                        alert('Error : ' + xhr.statusText);
                        document.location.reload(true);
                    }
                });
            });
        });
    </script>
@endsection
