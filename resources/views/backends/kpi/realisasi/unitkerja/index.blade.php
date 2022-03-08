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
                            <div class="panel-title-box">Data Realisasi KPI Unit Kerja</div>
                        </div>
                        @if(auth()->user()->UserRole->Role === 'Karyawan')
                            <div class="col-sm-9">
                                <div class="custom-button-container">
                                    <a href="{{ route('backends.kpi.realisasi.unitkerja.create') }}">
                                        <button class="btn btn-link">
                                            <img src="{{ asset('assets/img/ic_add.png') }}"> Add
                                        </button>
                                    </a>
                                    <a href="#">
                                        <button class="btn btn-link" id="cancel">
                                            <img src="{{ asset('assets/img/ic_add.png') }}" class="rotate-30"> Cancel
                                        </button>
                                    </a>
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
                                    <a href="#">
                                        <button class="btn btn-link" id="revisitarget">
                                            <img src="{{ asset('assets/img/ic_edit.png') }}"> Revisi Target
                                        </button>
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-3 {{ (auth()->user()->UserRole->Role !== 'Karyawan') ? 'col-sm-offset-9' : '' }}">
                            <div class="custom-button-container text-right">
                                <button class="btn btn-link" id="datatable-realisasikpi-refresh">
                                    <img src="{{ asset('assets/img/ic_refresh.png') }}">
                                </button>
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="realisasikpiunitkerja-table" class="table table-striped" style="width:1500px;">
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
                                    <th>Pencapaian</th>
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
            $('#realisasikpiunitkerja-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route("backends.kpi.realisasi.unitkerja") !!}',
                order: [[2, 'desc']],
                columns: [
                    { data: 'checkall', name: 'checkall', orderable: false},
                    { data: 'Aksi', orderable: false},
                    { data: 'statusdokumen.StatusDokumen', name: 'statusdokumen.StatusDokumen' },
                    { data: 'Tahun', name: 'Tahun'},
                    { data: 'periodeaktif.NamaPeriode', name: 'periodeaktif.NamaPeriode'},
                    { data: 'NPK', name: 'NPK'},
                    { data: 'karyawan.NamaKaryawan', name: 'karyawan.NamaKaryawan'},
                    { data: 'Grade', name: 'Grade'},
                    { data: 'masterposition.PositionTitle', name: 'masterposition.PositionTitle'},
                    { data: 'masterposition.unitkerja.Deskripsi', name: 'masterposition.unitkerja.Deskripsi'},
                    { data: 'Pencapaian', name: 'Pencapaian', searchable: false},
                    { data: 'CreatedOn', name: 'CreatedOn'}
                ]
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
                    window.location.href = baseurl + "/kpi/realisasi/unitkerja/" + id + '/editdetail';
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
                    window.location.href = baseurl + "/kpi/realisasi/unitkerja/" + id;
                }
            });

            // register realisasi
            $('#register').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.realisasi.individu.register') }}";
                var modalTitle = 'Register Realisasi KPI Unit Kerja';
                var modalContent = 'Apakah anda yakin untuk melakukan REGISTER Realisasi KPI Unit Kerja Anda?';
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#unregister').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.realisasi.individu.unregister') }}";
                var modalTitle = 'Unregister Realisasi KPI Unit Kerja';
                var modalContent = 'Apakah anda yakin untuk melakukan UNREGISTER Realisasi KPI Unit Kerja Anda?';
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#cancel').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.realisasi.individu.cancellation') }}";
                var modalTitle = 'Cancel Realisasi KPI Unit Kerja';
                var modalContent = 'Apakah anda yakin untuk melakukan CANCEL Realisasi KPI Unit Kerja Anda?';
                $('#formgroup-alasan').show();
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('.noCallButton').click(function () {
                $('#modalform').attr('action', '');
                $('.appendedInput').remove();
            });

            // call function
            function callAction(form, checked, action, title, content) {
                if(checked.length === 0) {
                    alert('Silakan pilih satu realisasi terlebih dahulu.');
                } else if(checked.length > 1) {
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
                    $('#actionConfirmModal').modal('show');
                    $('#callButton').click(function(event) {
                        event.preventDefault();
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
        });
    </script>
@endsection