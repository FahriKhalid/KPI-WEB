@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.master')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Data Validasi Unit Kerja</div>
                            @include('vendor.flash.message')
                        </div>
                        <div class="col-sm-6">
                            <div class="custom-button-container">
                                @if($data['position'] === 'generalmanager')
                                <button class="btn btn-link" id="approvevalidasi">
                                    <img src="{{ asset('assets/img/ic_confirm.png') }}"> Approve Validasi
                                </button>
                                @elseif($data['position'] === 'manager')
                                    <button class="btn btn-link" id="createvalidation">
                                        <img src="{{ asset('assets/img/ic_confirm.png') }}"> Buat Validasi
                                    </button>
                                    <button class="btn btn-link" id="editvalidation">
                                        <img src="{{ asset('assets/img/ic_edit.png') }}"> Edit Validasi
                                    </button>
                                    <button class="btn btn-link" id="cancelvalidation">
                                        <img src="{{ asset('assets/img/ic_add.png') }}" class="rotate-30"> Cancel Validasi
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-offset-3 col-sm-3">
                            <div class="custom-button-container text-right">
                                <button class="btn btn-link" title="Refresh data" id="datatable-validation-refresh">
                                    <img src="{{ asset('assets/img/ic_refresh.png') }}">
                                </button>
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="validation-table" class="table table-striped" width="100%">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Kode Unit Kerja</th>
                                    <th>Unit Kerja</th>
                                    <th>Tahun</th>
                                    <th>Periode</th>
                                    <th>Nilai KPI</th>
                                    <th>Unit Kerja Penilai</th>
                                    <th>Status</th>
                                    <th>Penilaian (%)</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-notification" id="actionApproveModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
        $(function() {
            $('#validation-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route("backends.realisasi.validasi.unitkerja.index") !!}',
                columns: [
                    { data: 'checkall', name: 'checkall', orderable: false},
                    { data: 'CostCenter', name: 'Ms_UnitKerja.CostCenter' },
                    { data: 'Deskripsi', name: 'Ms_UnitKerja.Deskripsi' },
                    { data: 'Tahun', name: 'Tr_KPIRealisasiHeader.Tahun', defaultContent: '-' },
                    { data: 'KodePeriode', name: 'VL_JenisPeriode.KodePeriode', defaultContent: '-' },
                    { data: 'NilaiAkhirNonTaskForce', name: 'Tr_KPIRealisasiHeader.NilaiAkhirNonTaskForce'},
                    { data: 'KodeUnitKerjaPenilai', name: 'Tr_ValidasiUnitKerja.KodeUnitKerjaPenilai', defaultContent: '-' },
                    { data: 'StatusDokumen', name: 'VL_StatusDokumen.StatusDokumen', defaultContent: '-' },
                    { data: 'ValidasiUnitKerja', name: 'Tr_ValidasiUnitKerja.ValidasiUnitKerja', defaultContent: '-' }
                ]
            });
        });

        $('#datatable-validation-refresh').click(function() {
            $('#validation-table').DataTable().ajax.reload();
        });

        $('#createvalidation').click(function() {
            var checked = $(':checkbox:checked');
            if(checked.length === 0) {
                alert('Silakan pilih satu realisasi unit kerja terlebih dahulu.');
            } else if(checked.length > 1) {
                alert('Hanya boleh satu realisasi unit kerja yang dipilih.');
            } else {
                var id = checked.val();
                var baseurl = "{{ url('/') }}";
                window.location.href = baseurl + "/kpi/realisasi/validasi/" + id + '/create';
            }
        });

        $('#editvalidation').click(function() {
            var checked = $(':checkbox:checked');
            if(checked.length === 0) {
                alert('Silakan pilih satu realisasi unit kerja terlebih dahulu.');
            } else if(checked.length > 1) {
                alert('Hanya boleh satu realisasi unit kerja yang dipilih.');
            } else {
                var id = checked.val();
                var baseurl = "{{ url('/') }}";
                window.location.href = baseurl + "/kpi/realisasi/validasi/" + id + '/edit';
            }
        });

        $('#cancelvalidation').click(function() {
            var form = $('#modalform');
            var checked = $(':checkbox:checked');
            var action = "{{ route('backends.realisasi.validasi.unitkerja.cancel') }}";
            var modalTitle = 'Cancel Validasi KPI Unit Kerja';
            var modalContent = 'Apakah anda yakin untuk melakukan Cancel Validasi Unit Kerja?';
            callAction(form, checked, action, modalTitle, modalContent);
        });

        $('#approvevalidasi').click(function() {
            var form = $('#modalform');
            var checked = $(':checkbox:checked');
            var action = "{{ route('backends.realisasi.validasi.unitkerja.approve') }}";
            var modalTitle = 'Approve Validasi KPI Unit Kerja';
            var modalContent = 'Apakah anda yakin untuk melakukan Approve Validasi Unit Kerja Anda?';
            callAction(form, checked, action, modalTitle, modalContent);
        });

        function callAction(form, checked, action, title, content) {
            if(checked.length === 0) {
                alert('Silakan pilih validasi unit kerja terlebih dahulu.');
            } else {
                form.attr('action', action);
                checked.each(function(i) {
                    if($(this).val() !== 'on') {
                        form.append('<input type="hidden" name="id[]" value="'+ $(this).data('validasi') + '" class="appendedInput">');
                    }
                });
                $('#actionModalTitle').html(title);
                $('#actionModalContent').html(content);
                $('#actionApproveModal').modal({backdrop: 'static', keyboard: false});
                $('#actionApproveModal').modal('show');
                $('#callButton').off('click');
                $('#callButton').click(function(event) {
                    event.preventDefault();
                    $(this).attr('disabled','disabled');
                    $.ajax({
                        url: action,
                        type: 'post',
                        data: form.serialize().replace(/%5B%5D/g, '[]'),
                        success: function(result) {
                            if(result.status) {
                                document.location.reload(true);
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
            }
        }

        $('.noCallButton').click(function () {
            $('#modalform').attr('action', '');
            $('.appendedInput').remove();
        });
    </script>
@endsection