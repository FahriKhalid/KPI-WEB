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
                        <a href="{{ route('backends.kpi.realisasi.individu.detailbawahanlangsung', ['id' => $data['header']->ID]) }}">Realisasi KPI</a>
                    </li>
                    <li>
                        <a href="{{ route('backends.kpi.realisasi.individu.documentbawahanlangsung', ['id' => $data['header']->ID]) }}">Dokumen</a>
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
                            <div class="panel-title-box no-border no-margin-bottom">Detail Realisasi KPI</div>
                        </div>

                        @include('backends.kpi.realisasi.sharedlayouts.detailrealisasitable')

                        <div class="text-center margin-top-15">
                            <a href="{{ route('backends.kpi.realisasi.individu.bawahanlangsung') }}" class="btn btn-default">Kembali</a>
                            @if($data['header']->IsUnitKerja == 1)
                                @if($data['header']->IDStatusDokumen == 2)
                                <button class="btn btn-success" id="approvedButton" data-uid="{{ $data['header']->ID }}" >Approved</button>
                                <button class="btn btn-danger" id="unapprovedButton" data-uid="{{ $data['header']->ID }}" >Unapproved</button>
                                @endif
                            @else
                                @if($data['header']->IDStatusDokumen == 2)
                                <button class="btn btn-success" id="confirmButton" data-uid="{{ $data['header']->ID }}" >Confirm</button>
                                <button class="btn btn-danger" id="unconfirmButton" data-uid="{{ $data['header']->ID }}" >Unconfirm</button>
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
                        <div class="form-group" id="formgroup-alasan-unconfirm" style="display: none;">
                            <label class="col-sm-12 control-label text-left">Catatan Unconfirm</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="CatatanUnconfirm" rows="2" id="inputAlasanUnconfirm" placeholder="Berikan catatan mengapa dokumen gagal dikonfirmasi"></textarea>
                            </div>
                        </div>
                        <div class="form-group" id="formgroup-alasan-unapproved" style="display: none;">
                            <label class="col-sm-12 control-label text-left">Catatan Unapproved</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="CatatanUnapproved" rows="2" id="inputAlasanUnapproved" placeholder="Berikan catatan mengapa dokumen gagal di-approve"></textarea>
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
            $('#detailrealisasi-table').DataTable({
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
            $('#confirmButton').click(function () {
                var form = $('#modalform');
                var action = "{{ route('backends.kpi.realisasi.individu.confirm') }}";
                var modalTitle = 'Confirm Realisasi KPI Individu';
                var modalContent = 'Apakah anda yakin untuk melakukan CONFIRM Realisasi KPI Individu kepada atasan Anda?';
                callAction($(this).data('uid'), form, action, modalTitle, modalContent, 'confirm');
            });

            $('#unconfirmButton').click(function () {
                var form = $('#modalform');
                var action = "{{ route('backends.kpi.realisasi.individu.unconfirm') }}";
                var modalTitle = 'Unconfirm Realisasi KPI Individu';
                var modalContent = 'Apakah anda yakin untuk melakukan UNCONFIRM Realisasi KPI Individu bawahan Anda dan menyerahkannya kembali sebagai DRAFT?';
                callAction($(this).data('uid'), form, action, modalTitle, modalContent, 'unconfirm');
            });

            $('#approvedButton').click(function () {
                var form = $('#modalform');
                var action = "{{ route('backends.kpi.realisasi.approve') }}";
                var modalTitle = 'Approve Realisasi KPI Unit Kerja';
                var modalContent = 'Apakah anda yakin untuk melakukan Approve Realisasi KPI Unit Kerja bawahan Anda?';
                callAction($(this).data('uid'), form, action, modalTitle, modalContent, 'approved');
            });

            $('#unapprovedButton').click(function () {
                var form = $('#modalform');
                var action = "{{ route('backends.kpi.realisasi.approve') }}";
                var modalTitle = 'Unapproved Realisasi KPI Unit Kerja';
                var modalContent = 'Apakah anda yakin untuk melakukan Unapproved Realisasi KPI Unit Kerja bawahan Anda dan menyerahkan kembali sebagai DRAFT?';
                callAction($(this).data('uid'), form, action, modalTitle, modalContent, 'unapproved');
            });

            function callAction(uid, form, action, title, content, state) {
                form.append('<input type="hidden" name="id[]" value="' + uid + '" class="appendedInput">');
                if (state === 'unconfirm') {
                    $('#formgroup-alasan-unconfirm').show();
                }
                if(state === 'unapproved') {
                    $('#formgroup-alasan-unapproved').show();
                }
                $('#actionModalTitle').html(title);
                $('#actionModalContent').html(content);
                $('#actionConfirmModal').modal({backdrop: 'static', keyboard: false});
                $('#actionConfirmModal').modal('show');
                $('#callButton').off('click');
                $('#callButton').click(function(event) {
                    event.preventDefault();
                    if($('#inputAlasanUnconfirm').val() == '' && $('#formgroup-alasan-unconfirm').is(':visible')) {
                        alert('Catatan unconfirm harus diisi.');
                    } else if($('#inputAlasanUnapproved').val() == '' && $('#formgroup-alasan-unapproved').is(':visible')) {
                        alert('Catatan unapprove harus diisi.')
                    } else {
                        $(this).attr('disabled','disabled');
                        $.ajax({
                            url: action,
                            type: 'post',
                            data: form.serialize(),
                            success: function(result) {
                                if(result.status) {
                                    window.location.href = "{{ route('backends.kpi.realisasi.individu.bawahanlangsung') }}";
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
                $('#formgroup-alasan-unconfirm').hide();
                $('#formgroup-alasan-unapproved').hide();
                $('.appendedInput').remove();
            });
        });
    </script>
@endsection