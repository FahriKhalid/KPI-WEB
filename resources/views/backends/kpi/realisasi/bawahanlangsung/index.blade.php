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
                        <div class="panel-title-box">Data Realisasi KPI Bawahan Langsung</div>
                    </div>
                    <div class="filter-kpi">
                        <div class="col-sm-12">
                            <div class="row">
                                <form action="{{ route('backends.kpi.realisasi.individu.bawahanlangsung') }}" method="get">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Jenis Realisasi :</label>
                                            <select name="jeniskpi" class="form-control">
                                                <option value="individu" {{ (request('jeniskpi') == 'individu') ? 'selected' : '' }}>KPI Individu</option>
                                                <option value="unitkerja" {{ (request('jeniskpi') == 'unitkerja') ? 'selected' : '' }}>KPI Unit Kerja</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Tahun</label>
                                            <input type="text" name="tahunperiode" placeholder="Tahun Rencana" class="form-control" value="{{ request('tahunperiode') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Periode</label>
                                            <select class="form-control" name="jenisperiode">
                                                <option value="all" {{ (request('jenisperiode') == '' || request('jenisperiode') == 'all') ? 'selected' : '' }}>Pilih Periode</option>
                                                @foreach($data['jenisperiode'] as $jenisperiode)
                                                    <option value="{{ $jenisperiode->ID }}" {{ (request('jenisperiode') == $jenisperiode->ID) ? 'selected' : '' }}>{{ $jenisperiode->NamaPeriodeKPI }} ({{ $jenisperiode->KodePeriode }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-yellow" type="submit">Tampilkan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if(auth()->user()->UserRole->Role === 'Karyawan')
                            <div class="col-sm-9">
                                <div class="custom-button-container">
                                    @if(request('jeniskpi') == 'individu' || request('jeniskpi') == '' || request('jeniskpi') == 'all')
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
                                    @endif
                                    @if(request('jeniskpi') == 'unitkerja' && $data['allowApproval'])
                                        <a href="#">
                                            <button class="btn btn-link" id="approveunitkerja">
                                                <img src="{{ asset('assets/img/ic_confirm.png') }}"> Approve KPI Unit Kerja
                                            </button>
                                        </a>
                                        <a href="#">
                                            <button class="btn btn-link" id="unapproveunitkerja">
                                                <img src="{{ asset('assets/img/ic_unconfirm.png') }}"> Unapprove KPI Unit Kerja
                                            </button>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-3 {{ (auth()->user()->UserRole->Role !== 'Karyawan') ? 'col-sm-offset-9' : '' }}">
                            <div class="custom-button-container text-right">
                                <button class="btn btn-link" id="datatable-realisasikpibawahanlangsung-refresh">
                                    <img src="{{ asset('assets/img/ic_refresh.png') }}">
                                </button>
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="realisasikpibawahanlangsung-table" class="table table-striped" style="width:100%;">
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
                                    @if(request('jeniskpi') == 'unitkerja')
                                        <th>Pencapaian</th>
                                    @else
                                        <th>KPI</th>
                                        <th>KPI + Task Force</th>
                                        <th>KPI Tervalidasi</th>
                                    @endif
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
                        <div class="form-group" id="formgroup-alasan-unapproved" style="display: none;">
                            <label class="col-sm-12 control-label text-left">Catatan Unapproved</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="CatatanUnapprove" rows="2" id="inputAlasanUnapproved" placeholder="Berikan catatan mengapa dokumen di unapprove"></textarea>
                            </div>
                        </div>
                        <div class="form-group" id="formgroup-alasan-unconfirmed" style="display: none;">
                            <label class="col-sm-12 control-label text-left">Catatan Unconfirmed</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="CatatanUnconfirm" rows="2" id="inputAlasanUnconfirm" placeholder="Berikan catatan mengapa dokumen di unconfirm"></textarea>
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
            $('#realisasikpibawahanlangsung-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route("backends.kpi.realisasi.individu.bawahanlangsung", request()->all()) !!}',
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
                    @if(request('jeniskpi') == 'unitkerja')
                    {data: 'Pencapaian', name: 'Pencapaian', defaultContent: '-'},
                    @else
                    { data: 'NilaiAkhirNonTaskForce', name: 'NilaiAkhirNonTaskForce', searchable: false, defaultContent: '-' },
                    { data: 'NilaiAkhir', name: 'NilaiAkhir', searchable: false, defaultContent: '-' },
                    { data: 'NilaiValidasiNonTaskForce', name: 'NilaivalidasiNonTaskForce', searchable: false, defaultContent: '-' },
                    @endif
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

            $('#confirm').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.realisasi.individu.confirm') }}";
                var modalTitle = 'Confirm Realisasi KPI Individu';
                var modalContent = 'Apakah anda yakin untuk melakukan CONFIRM Realisasi KPI Individu kepada atasan Anda?';
                $('#formgroup-alasan-unconfirmed').hide();
                $('#formgroup-alasan-unapproved').hide();
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#unconfirm').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.realisasi.individu.unconfirm') }}";
                var modalTitle = 'Unconfirm Realisasi KPI';
                var modalContent = 'Apakah anda yakin untuk melakukan UNCONFIRM Realisasi KPI Individu bawahan Anda dan menyerahkannya kembali sebagai DRAFT?';
                $('#formgroup-alasan-unconfirmed').show();
                $('#formgroup-alasan-unapproved').hide();
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#approveunitkerja').click(function () {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.realisasi.approve') }}";
                var modalTitle = 'Approve Realisasi KPI Unit Kerja';
                var modalContent = 'Apakah anda yakin untuk melakukan Approve Realisasi KPI Unit Kerja bawahan Anda?';
                $('#formgroup-alasan-unconfirmed').hide();
                $('#formgroup-alasan-unapproved').hide();
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#unapproveunitkerja').click(function () {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.realisasi.unapprove') }}";
                var modalTitle = 'Unapproved Realisasi KPI Unit Kerja';
                var modalContent = 'Apakah anda yakin untuk melakukan Unapproved Realisasi KPI Unit Kerja bawahan Anda dan menyerahkan kembali sebagai DRAFT?';
                $('#formgroup-alasan-unconfirmed').hide();
                $('#formgroup-alasan-unapproved').show();
                callAction(form, checked, action, modalTitle, modalContent);
            });
        });

        $('#datatable-realisasikpibawahanlangsung-refresh').click(function() {
            $('#realisasikpibawahanlangsung-table').DataTable().ajax.reload();
        });

        $('#detaildata').click(function() {
            var checked = $(':checkbox:checked');
            if (checked.length === 0) {
                alert('Silakan pilih satu realisasi terlebih dahulu.');
            } else if (checked.length > 1) {
                alert('Hanya boleh satu realisasi yang dipilih.');
            } else {
                var id = checked.val();
                var baseurl = "{{ url('/') }}";
                window.location.href = baseurl + "/kpi/realisasi/bawahanlangsung/" + id;
            }
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
                $('#actionConfirmModal').modal({backdrop: 'static', keyboard: false})
                $('#actionConfirmModal').modal('show');
                $('#callButton').off('click');
                $('#callButton').click(function(event) {
                    event.preventDefault();
                    if($('#inputAlasanUnconfirm').val() == '' && $('#formgroup-alasan-unconfirmed').is(':visible')) {
                        alert('Catatan unconfirm harus diisi.');
                    } else if($('#inputAlasanUnapproved').val() == '' && $('#formgroup-alasan-unapproved').is(':visible')) {
                        alert('Catatan unapprove harus diisi.')
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