@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.rencana')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Filter Data</div>
                            <form class="form-inline" method="get">
                                <input type="number" class="form-control" name="tahun" value="{{ request('tahun') }}" placeholder="Tahun Rencana">
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> </button>
                            </form>
                        </div>
                        <div class="col-sm-12">
                            <div class="custom-button-container">
                                @can('create', 'Pkt\Domain\Rencana\Entities\HeaderRencanaKPI')
                                <a href="{{ route('backends.kpi.rencana.individu.create.step1') }}">
                                    <button class="btn btn-link">
                                        <img src="{{ asset('assets/img/ic_add.png') }}"> Tambah
                                    </button>
                                </a>
                                @endcan
                                @can('cancel', 'Pkt\Domain\Rencana\Entities\HeaderRencanaKPI')
                                <a href="#">
                                    <button class="btn btn-link" id="cancel">
                                        <img src="{{ asset('assets/img/ic_add.png') }}" class="rotate-30"> Cancel
                                    </button>
                                </a>
                                @endcan
                                @can('register', 'Pkt\Domain\Rencana\Entities\HeaderRencanaKPI')
                                <a href="#">
                                    <button class="btn btn-link" id="register">
                                        <img src="{{ asset('assets/img/ic_register.png') }}"> Register
                                    </button>
                                </a>
                                <a href="#">
                                    <button class="btn btn-link" id="unregister">
                                        <img src="{{ asset('assets/img/ic_unregister.png') }}"> Unregister
                                    </button>
                                </a>
                                @endcan
                                @can('revisiTarget', 'Pkt\Domain\Rencana\Entities\HeaderRencanaKPI')
                                <a href="#">
                                    <button class="btn btn-link" id="revisitarget">
                                        <img src="{{ asset('assets/img/ic_edit.png') }}"> Revisi Target
                                    </button>
                                </a>
                                @endcan
                                @can('adminConfirmation', 'Pkt\Domain\Rencana\Entities\HeaderRencanaKPI')
                                <a href="#">
                                    <button class="btn btn-link" id="confirm">
                                        <img src="{{ asset('assets/img/ic_confirm.png') }}"> Confirm
                                    </button>
                                </a>
                                <a href="#">
                                    <button class="btn btn-link" id="unconfirmButton">
                                        <img src="{{ asset('assets/img/ic_unconfirm.png') }}"> Unconfirm
                                    </button>
                                </a>
                                @endcan
                                @can('adminApproval', 'Pkt\Domain\Rencana\Entities\HeaderRencanaKPI')
                                <a href="#">
                                    <button class="btn btn-link" id="approve">
                                        <img src="{{ asset('assets/img/ic_confirm.png') }}"> Approve
                                    </button>
                                </a>
                                <a href="#">
                                    <button class="btn btn-link" id="unapprove">
                                        <img src="{{ asset('assets/img/ic_unconfirm.png') }}"> Unapprove
                                    </button>
                                </a>
                                @endcan
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="rencanakpiindividu-table" class="table table-striped" style="width: 100%">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Aksi</th>
                                    <th>Status</th>
                                    <th>Tahun</th>
                                    <th>NPK</th>
                                    <th>Nama Karyawan</th>
                                    <th>Grade</th>
                                    <th>Jabatan</th>
                                    <th>Unit Kerja</th>
                                    <th>Created By</th>
                                    <th>Created On</th>
                                </tr>
                                </thead>
                            </table>
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
                            <label class="col-sm-12 control-label text-left">Catatan Cancel</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="AlasanCancel" rows="2" id="inputAlasanCancel" placeholder="Berikan catatan mengapa dokumen di cancel"></textarea>
                            </div>
                        </div>
                        <div class="form-group" id="formgroup-alasan-unconfirm" style="display: none;">
                            <label class="col-sm-12 control-label text-left">Catatan Unconfirm</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="CatatanUnconfirm" rows="2" id="inputAlasanUnconfirm" placeholder="Berikan catatan mengapa dokumen di unconfirm"></textarea>
                            </div>
                        </div>
                        <div class="form-group" id="formgroup-alasan-unapprove" style="display: none;">
                            <label class="col-sm-12 control-label text-left">Catatan Unapprove</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="CatatanUnapprove" rows="2" id="inputAlasanUnapprove" placeholder="Berikan catatan mengapa dokumen di unapprove"></textarea>
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
            $('#rencanakpiindividu-table').DataTable({ 
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{!! route("backends.kpi.rencana.individu", request()->all()) !!}',
                order: [[2, 'desc']],
                columns: [
                    { data: 'checkall', name: 'checkall', orderable: false},
                    { data: 'Aksi', orderable: false},
                    { data: 'StatusDokumen', name: 'VL_StatusDokumen.StatusDokumen' },
                    { data: 'Tahun', name: 'Tr_KPIRencanaHeader.Tahun'},
                    { data: 'NPK', name: 'Tr_KPIRencanaHeader.NPK'},
                    { data: 'NamaKaryawan', name: 'Ms_Karyawan.NamaKaryawan'},
                    { data: 'Grade', name: 'Tr_KPIRencanaHeader.Grade'},
                    { data: 'PositionTitle', name: 'Ms_MasterPosition.PositionTitle'},
                    { data: 'Deskripsi', name: 'Ms_UnitKerja.Deskripsi'},
                    { data: 'CreatedBy', name: 'Tr_KPIRencanaHeader.CreatedBy', searchable: false},
                    { data: 'CreatedOn', name: 'Tr_KPIRencanaHeader.CreatedOn'}
                ],
                dom: 'lBfrtip',
                buttons: [ 
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible',
                            orthogonal: 'excel',
                            modifier : {
                                 order : 'index', // 'current', 'applied','index', 'original'
                                 page : 'all', // 'all', 'current'
                                 search : 'none' // 'none', 'applied', 'removed'
                             },
                        },
                    }, 
                ],
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
            });

            $('#datatable-rencanakpi-refresh').click(function() {
                $('#rencanakpiindividu-table').DataTable().ajax.reload();
            });

            $('#revisitarget').click(function() {
                var checked = $(':checkbox:checked');
                if(checked.length === 0) {
                    alert('Silakan pilih satu rencana terlebih dahulu.');
                } else if(checked.length > 1) {
                    alert('Hanya boleh satu rencana yang dipilih.');
                } else {
                    var id = checked.val();
                    var baseurl = "{{ url('/') }}";
                    window.location.href = baseurl + "/kpi/rencana/individu/" + id + '/editdetail';
                }
            });

            $('#detaildata').click(function() {
                var checked = $(':checkbox:checked');
                if(checked.length === 0) {
                    alert('Silakan pilih satu rencana terlebih dahulu.');
                } else if(checked.length > 1) {
                    alert('Hanya boleh satu rencana yang dipilih.');
                } else {
                    var id = checked.val();
                    var baseurl = "{{ url('/') }}";
                    window.location.href = baseurl + "/kpi/rencana/individu/" + id;
                }
            });

            // register rencana
            $('#register').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.rencana.individu.register') }}";
                var modalTitle = 'Register Rencana KPI Individu';
                var modalContent = 'Apakah anda yakin untuk melakukan REGISTER Rencana KPI Individu Anda?';
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#unregister').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.rencana.individu.unregister') }}";
                var modalTitle = 'Unregister Rencana KPI Individu';
                var modalContent = 'Apakah anda yakin untuk melakukan UNREGISTER Rencana KPI Individu Anda?';
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#cancel').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.rencana.individu.cancellation') }}";
                var modalTitle = 'Cancel Rencana KPI Individu';
                var modalContent = 'Apakah anda yakin untuk melakukan CANCEL Rencana KPI Individu Anda?';
                $('#formgroup-alasan').show();
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#confirm').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.rencana.individu.confirm') }}";
                var modalTitle = 'Confirm Rencana KPI Individu';
                var modalContent = 'Apakah anda yakin untuk melakukan CONFIRM Rencana KPI?';
                $('#formgroup-alasan-unconfirm').hide();
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#unconfirmButton').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.rencana.individu.unconfirm') }}";
                var modalTitle = 'Unconfirm Rencana KPI';
                var modalContent = 'Apakah anda yakin untuk melakukan UNCONFIRM Rencana KPI?';
                $('#formgroup-alasan-unconfirm').show();
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#approve').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.rencana.approve') }}";
                var modalTitle = 'Approve Rencana KPI Individu';
                var modalContent = 'Apakah anda yakin untuk melakukan APPROVED Rencana KPI?';
                $('#formgroup-alasan-unapprove').hide();
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#unapprove').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.rencana.unapprove') }}";
                var modalTitle = 'Unapprove Rencana KPI';
                var modalContent = 'Apakah anda yakin untuk melakukan UNAPPROVED Rencana KPI?';
                $('#formgroup-alasan-unapprove').show();
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('.noCallButton').click(function () {
                $('#modalform').attr('action', '');
                $('.appendedInput').remove();
                $('#formgroup-alasan').hide();
                $('#inputAlasanCancel').val('');
                $('#formgroup-alasan-unconfirm').hide();
                $('#inputAlasanUnconfirm').val('');
                $('#formgroup-alasan-unapprove').hide();
                $('#inputAlasanUnapprove').val('');
            });

            // call function
            function callAction(form, checked, action, title, content) {
                var role = '{{ auth()->user()->UserRole->Role }}';
                if(checked.length === 0) {
                    alert('Silakan pilih satu rencana terlebih dahulu.');
                } else if(checked.length > 1 && role == 'Karyawan') {
                    alert('Hanya boleh satu rencana yang dipilih.');
                } else {
                    form.attr('action', action);
                    checked.each(function(i) {
                        if($(this).val() !== 'on') {
                            form.append('<input type="hidden" name="id[]" value="'+ $(this).val() + '" class="appendedInput">')
                        }
                    });
                    $('#actionModalTitle').html(title);
                    $('#actionModalContent').html(content);
                    $('#actionConfirmModal').modal({backdrop: 'static', keyboard: false});
                    $('#actionConfirmModal').modal('show');
                    $('#callButton').off('click');
                    $('#callButton').click(function(event) {
                        event.preventDefault();
                        if($('#inputAlasanCancel').val() == '' && $('#formgroup-alasan').is(':visible')) {
                            alert('Catatan cancel harus diisi.');
                        } else if($('#inputAlasanUnconfirm').val() == '' && $('#formgroup-alasan-unconfirm').is(':visible')) {
                            alert('Catatan unconfirm harus diisi.');
                        } else if($('#inputAlasanUnapprove').val() == '' && $('#formgroup-alasan-unapprove').is(':visible')) {
                            alert('Catatan unapprove harus diisi.');
                        } else {
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
                        }
                    });
                }
            }
        });
    </script>
@endsection