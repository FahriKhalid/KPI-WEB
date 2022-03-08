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
                    <li>
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
                            <div class="panel-title-box no-border">Data Detail Rencana KPI <span class="label label-info pull-right">{{ strtoupper($data['header']->statusdokumen->StatusDokumen) }}</span></div>
                        </div>
                        @include('backends.kpi.rencana.sharedlayouts.metadataheaderdetail')
                    </div>
                </div>
                <div class="panel panel-default panel-box panel-create">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box no-border no-margin-bottom">Detail KPI</div>
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
                        <div class="col-lg-offset-2 col-sm-2">
                            <div class="custom-button-container text-right">
                                <a href="{{ route('backends.kpi.rencana.individu.printbawahantaklangsung', ['id' => $data['header']->ID, 'jenisrencana' => 'individu']) }}">
                                    <button class="btn btn-link" {{$data['header']->IDStatusDokumen != 4 ? 'disabled':''}}>
                                        <img src="{{ asset('assets/img/ic_print.png') }}"> Print
                                    </button>
                                </a>
                            </div>
                        </div>
                        @include('backends.kpi.rencana.sharedlayouts.detailrencanatable')
                        <div class="text-center margin-top-15">
                            <a href="{{ route('backends.kpi.rencana.individu.bawahantaklangsung') }}" class="btn btn-default">Kembali</a>
                            @if($data['header']->IDStatusDokumen == 3)
                                <button class="btn btn-success" id="approveButton" data-uid="{{ $data['header']->ID }}" >Approve</button>
                                <button class="btn btn-danger" id="unapproveButton" data-uid="{{ $data['header']->ID }}" >Unapprove</button>
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
                        <div class="form-group" id="formgroup-alasan" style="display: none;">
                            <label class="col-sm-12 control-label text-left">Catatan Unapprove</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="CatatanUnapprove" rows="2" id="inputAlasanUnapprove" placeholder="Berikan catatan mengapa dokumen gagal di approve"></textarea>
                            </div>
                        </div>
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
        $(function() {
            $('#detailrencana-table').DataTable({
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

        $(function() {
            $('#approveButton').click(function () {
                var form = $('#modalform');
                var action = "{{ route('backends.kpi.rencana.approve') }}";
                var modalTitle = 'Approve Rencana KPI Individu';
                var modalContent = 'Apakah anda yakin untuk melakukan APPROVE Rencana KPI Individu kepada atasan Anda?';
                callAction($(this).data('uid'), form, action, modalTitle, modalContent, 'approve');
            });

            $('#unapproveButton').click(function () {
                var form = $('#modalform');
                var action = "{{ route('backends.kpi.rencana.unapprove') }}";
                var modalTitle = 'Unapprove Rencana KPI Individu';
                var modalContent = 'Apakah anda yakin untuk melakukan UNAPPROVE Rencana KPI Individu bawahan Anda dan menyerahkannya kembali sebagai DRAFT?';
                callAction($(this).data('uid'), form, action, modalTitle, modalContent, 'unapprove');
            });

            function callAction(uid, form, action, title, content, state) {
                form.append('<input type="hidden" name="id" value="' + uid + '" class="appendedInput">');
                if (state === 'unapprove') {
                    $('#formgroup-alasan').show();
                }
                $('#actionModalTitle').html(title);
                $('#actionModalContent').html(content);
                $('#actionConfirmModal').modal({backdrop: 'static', keyboard: false}); 
                $('#actionConfirmModal').modal('show');
                $('#callButton').off('click');
                $('#callButton').click(function(event) {
                    event.preventDefault();
                    if($('#inputAlasanUnapprove').val() == '' && $('#formgroup-alasan').is(':visible')) {
                        alert('Catatan unapprove harus diisi.');
                    } else {
                        $(this).attr('disabled','disabled');
                        $.ajax({
                            url: action,
                            type: 'post',
                            data: form.serialize(),
                            success: function(result) {
                                if(result.status) {
                                    window.location.href = "{{ route('backends.kpi.rencana.individu.bawahantaklangsung') }}";
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
                    }
                });
            }

            $('.noCallButton').click(function () {
                $('#modalform').attr('action', '');
                $('#formgroup-alasan').hide();
                $('.appendedInput').remove();
            });
        });
    </script>
@endsection
