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
                    <div class="col-sm-12">
                        <div class="panel-title-box">Data Realisasi KPI Bawahan Tidak Langsung</div>
                    </div>
                    <div class="col-sm-12 margin-top-15">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="alert alert-approved">
                                    <h4 class="title-alert">Approved</h4>
                                    <h2 class="content-alert">{{ $data['approved'] }}</h2>
                                    <div class="icon-alert">
                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="alert alert-pending">
                                    <h4 class="title-alert">Confirmed</h4>
                                    <h2 class="content-alert">{{ $data['confirmed'] }}</h2>
                                    <div class="icon-alert">
                                        <i class="fa fa-hourglass-start" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if(auth()->user()->UserRole->Role === 'Karyawan')
                            <div class="col-sm-9">
                                <div class="custom-button-container">
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
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-3 {{ (auth()->user()->UserRole->Role !== 'Karyawan') ? 'col-sm-offset-9' : '' }}">
                            <div class="custom-button-container text-right">
                                <button class="btn btn-link" id="datatable-realisasikpibawahantaklangsung-refresh">
                                    <img src="{{ asset('assets/img/ic_refresh.png') }}">
                                </button>
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="realisasikpibawahantaklangsung-table" class="table table-striped" style="width:100%;">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Aksi</th>
                                    <th>Status</th>
                                    <th>Tahun</th>
                                    <th>Periode</th>
                                    <th>Jenis Realisasi</th>
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
            $('#realisasikpibawahantaklangsung-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route("backends.kpi.realisasi.individu.bawahantaklangsung") !!}',
                order: [[2, 'desc']],
                columns: [
                    { data: 'checkall', name: 'checkall', orderable: false},
                    { data: 'Aksi', orderable: false},
                    { data: 'statusdokumen.StatusDokumen', name: 'statusdokumen.StatusDokumen' },
                    { data: 'Tahun', name: 'Tahun'},
                    { data: 'jenisperiode.KodePeriode', name: 'jenisperiode.KodePeriode'},
                    { data: 'IsUnitKerja', name: 'IsUnitKerja'},
                    { data: 'NPK', name: 'NPK'},
                    { data: 'karyawan.NamaKaryawan', name: 'karyawan.NamaKaryawan'},
                    { data: 'Grade', name: 'Grade'},
                    { data: 'masterposition.PositionTitle', name: 'masterposition.PositionTitle'},
                    { data: 'masterposition.unitkerja.Deskripsi', name: 'masterposition.unitkerja.Deskripsi'},
                    { data: 'NilaiAkhirNonTaskForce', name: 'NilaiAkhirNonTaskForce', searchable: false, defaultContent: '-' },
                    { data: 'NilaiAkhir', name: 'NilaiAkhir', searchable: false, defaultContent: '-' },
                    { data: 'NilaiValidasiNonTaskForce', name: 'NilaiValidasiNonTaskForce', searchable: false, defaultContent: '-' },
                    { data: 'CreatedOn', name: 'CreatedOn'}
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

            $('#datatable-realisasikpibawahantaklangsung-refresh').click(function() {
                $('#realisasikpibawahantaklangsung-table').DataTable().ajax.reload();
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
                    window.location.href = baseurl + "/kpi/realisasi/bawahantaklangsung/" + id;
                }
            });

            $('#approve').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.realisasi.approve') }}";
                var modalTitle = 'Approve Realisasi KPI Individu';
                var modalContent = 'Apakah anda yakin untuk melakukan APPROVED Realisasi KPI bawahan Anda?';
                $('#formgroup-alasan').hide();
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#unapprove').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.realisasi.unapprove') }}";
                var modalTitle = 'Unapprove Realisasi KPI';
                var modalContent = 'Apakah anda yakin untuk melakukan UNAPPROVED Realisasi KPI bawahan Anda?';
                $('#formgroup-alasan').show();
                callAction(form, checked, action, modalTitle, modalContent);
            });
        });

        $('.noCallButton').click(function () {
            $('#modalform').attr('action', '');
            $('.appendedInput').remove();
        });

        // call function
        function callAction(form, checked, action, title, content) {
            if(checked.length === 0) {
                alert('Silakan pilih realisasi terlebih dahulu.');
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
                    if ($('#inputAlasanUnapprove').val() == '' && $('#formgroup-alasan').is(':visible')) {
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
    </script>
@endsection