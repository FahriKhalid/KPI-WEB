@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.realisasi')
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
                                {!! Form::select('kodeperiode', $data['jenisperiode'], [], ['class' => 'form-control']) !!}
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> </button>
                            </form>
                        </div>
                        <div class="col-sm-12">
                            <div class="custom-button-container">
                                @can('create', \Pkt\Domain\Realisasi\Entities\HeaderRealisasiKPI::class)
                                <a href="{{ route('backends.kpi.realisasi.create') }}">
                                    <button class="btn btn-link">
                                        <img src="{{ asset('assets/img/ic_add.png') }}"> Add
                                    </button>
                                </a>
                                @endcan
                                @can('cancel', \Pkt\Domain\Realisasi\Entities\HeaderRealisasiKPI::class)
                                <a href="#">
                                    <button class="btn btn-link" id="cancel">
                                        <img src="{{ asset('assets/img/ic_add.png') }}" class="rotate-30"> Cancel
                                    </button>
                                </a>
                                @endcan
                                @can('register', \Pkt\Domain\Realisasi\Entities\HeaderRealisasiKPI::class)
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
                                @can('revisiRealisasi', \Pkt\Domain\Realisasi\Entities\HeaderRealisasiKPI::class)
                                <a href="#">
                                    <button class="btn btn-link" id="revisitarget">
                                        <img src="{{ asset('assets/img/ic_edit.png') }}"> Revisi Realisasi
                                    </button>
                                </a>
                                @endcan
                                @can('adminConfirmation', \Pkt\Domain\Realisasi\Entities\HeaderRealisasiKPI::class)
                                <a href="#">
                                    <button class="btn btn-link" id="confirm">
                                        <img src="{{ asset('assets/img/ic_confirm.png') }}"> Confirm
                                    </button>
                                </a>
                                <a href="#">
                                    <button class="btn btn-link" id="unconfirm">
                                        <img src="{{ asset('assets/img/ic_unconfirm.png') }}"> Unconfirm
                                    </button>
                                </a>
                                @endcan
                                @can('adminApproval', \Pkt\Domain\Realisasi\Entities\HeaderRealisasiKPI::class)
                                <a href="#">
                                    <button class="btn btn-link" id="approve">
                                        <img src="{{ asset('assets/img/ic_confirm.png') }}"> Approved
                                    </button>
                                </a>
                                <a href="#">
                                    <button class="btn btn-link" id="unapprove">
                                        <img src="{{ asset('assets/img/ic_unconfirm.png') }}"> Unapproved
                                    </button>
                                </a>
                                @endcan
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="realisasikpiindividu-table" class="table table-striped" style="width:100%;">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Aksi</th>
                                    <th>Status</th>
                                    <th>Tahun</th>
                                    <th>Periode</th>
                                    <th>NPK</th>
                                    <th>Nama Karyawan</th>
                                    <th>Grade</th>
                                    <th>Jabatan</th>
                                    <th>Unit Kerja</th>
                                    <th>KPI</th>
                                    <th>KPI + Task Force</th>
                                    <th>KPI Tervalidasi</th>
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
            $('#realisasikpiindividu-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route("backends.kpi.realisasi.individu", request()->all()) !!}',
                order: [[2, 'desc']],
                columns: [
                    { data: 'checkall', name: 'checkall', orderable: false},
                    { data: 'Aksi', orderable: false},
                    { data: 'StatusDokumen', name: 'VL_StatusDokumen.StatusDokumen' },
                    { data: 'Tahun', name: 'Tahun'},
                    { data: 'KodePeriode', name: 'Tr_KPIRealisasiHeader.KodePeriode'},
                    { data: 'NPK', name: 'Tr_KPIRealisasiHeader.NPK'},
                    { data: 'NamaKaryawan', name: 'Ms_Karyawan.NamaKaryawan'},
                    { data: 'Grade', name: 'Grade'},
                    { data: 'PositionTitle', name: 'Ms_MasterPosition.PositionTitle'},
                    { data: 'Deskripsi', name: 'Ms_UnitKerja.Deskripsi'},
                    { data: 'NilaiAkhirNonTaskForce', name: 'Tr_KPIRealisasiHeader.NilaiAkhirNonTaskForce', searchable: false},
                    { data: 'NilaiAkhir', name: 'Tr_KPIRealisasiHeader.NilaiAkhir'},
                    { data: 'NilaiValidasiNonTaskForce', name: 'Tr_KPIRealisasiHeader.NilaiValidasiNonTaskForce', sDefaultContent: '-', searchable: false},
                    { data: 'CreatedOn', name: 'Tr_KPIRealisasiHeader.CreatedOn'}
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

            $('#datatable-realisasikpi-refresh').click(function() {
                $('#realisasikpiindividu-table').DataTable().ajax.reload();
            });

            $('#revisitarget').click(function() {
                var checked = $(':checkbox:checked');
                if(checked.length === 0) {
                    alert('Silakan pilih satu realisasi terlebih dahulu.');
                } else if(checked.length > 1) {
                    alert('Hanya boleh satu realisasi yang dipilih.');
                } else {
                    var id = checked.val();
                    var baseurl = "{{ url('/') }}";
                    window.location.href = baseurl + "/kpi/realisasi/individu/" + id + '/editdetail';
                }
            });

            $('#detaildata').click(function() {
                var checked = $(':checkbox:checked');
                if(checked.length === 0) {
                    alert('Silakan pilih satu realisasi terlebih dahulu.');
                } else if(checked.length > 1) {
                    alert('Hanya boleh satu realisasi yang dipilih.');
                } else {
                    var id = checked.val();
                    var baseurl = "{{ url('/') }}";
                    window.location.href = baseurl + "/kpi/realisasi/individu/" + id;
                }
            });

            // register realisasi
            $('#register').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.realisasi.individu.register') }}";
                var modalTitle = 'Register Realisasi KPI Individu';
                var modalContent = 'Apakah anda yakin untuk melakukan REGISTER Realisasi KPI Individu Anda?';
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#unregister').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.realisasi.individu.unregister') }}";
                var modalTitle = 'Unregister Realisasi KPI Individu';
                var modalContent = 'Apakah anda yakin untuk melakukan UNREGISTER Realisasi KPI Individu Anda?';
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#cancel').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.realisasi.individu.cancellation') }}";
                var modalTitle = 'Cancel Realisasi KPI Individu';
                var modalContent = 'Apakah anda yakin untuk melakukan CANCEL Realisasi KPI Individu Anda?';
                $('#formgroup-alasan').show();
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#confirm').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.realisasi.individu.confirm') }}";
                var modalTitle = 'Confirm Realisasi KPI Individu';
                var modalContent = 'Apakah anda yakin untuk melakukan CONFIRM Realisasi KPI Individu?';
                $('#formgroup-alasan-unconfirm').hide();
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#unconfirm').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.realisasi.individu.unconfirm') }}";
                var modalTitle = 'Unconfirm Realisasi KPI';
                var modalContent = 'Apakah anda yakin untuk melakukan UNCONFIRM Realisasi KPI Individu dan menyerahkannya kembali sebagai DRAFT?';
                $('#formgroup-alasan-unconfirm').show();
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#approve').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.realisasi.approve') }}";
                var modalTitle = 'Approve Realisasi KPI Individu';
                var modalContent = 'Apakah anda yakin untuk melakukan APPROVED Realisasi KPI?';
                $('#formgroup-alasan-unapprove').hide();
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#unapprove').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.realisasi.unapprove') }}";
                var modalTitle = 'Unapprove Realisasi KPI';
                var modalContent = 'Apakah anda yakin untuk melakukan UNAPPROVED Realisasi KPI?';
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
                    alert('Silakan pilih satu realisasi terlebih dahulu.');
                } else if(checked.length > 1 && role == 'Karyawan') {
                    alert('Hanya boleh satu realisasi yang dipilih.');
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