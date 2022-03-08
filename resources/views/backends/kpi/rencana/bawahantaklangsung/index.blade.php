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
                    <div class="col-sm-12">
                        <div class="panel-title-box">Data Rencana KPI Bawahan Tidak Langsung</div>
                    </div>
                    <div class="col-sm-12 margin-top-15">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="alert alert-pending">
                                        <h4 class="title-alert">Menunggu Approval</h4>
                                        <h2 class="content-alert">{{ $data['waitingApprove'] }}</h2>
                                        <div class="icon-alert">
                                            <i class="fa fa-hourglass-start" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="alert alert-approved">
                                        <h4 class="title-alert">Telah Di-approved</h4>
                                        <h2 class="content-alert">{{ $data['approved'] }}</h2>
                                        <div class="icon-alert">
                                            <i class="fa fa-check" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Filter Data</div>
                            <form class="form-inline" method="get">
                                <input type="number" class="form-control" name="tahun" value="{{ request('tahun') }}" placeholder="Tahun Rencana">
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> </button>
                            </form>
                        </div>
                        @if(auth()->user()->UserRole->Role === 'Karyawan' || auth()->user()->UserRole->ID == 8)
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
                        <div class="col-sm-3 {{ (auth()->user()->UserRole->Role !== 'Karyawan' && auth()->user()->UserRole->ID != 8) ? 'col-sm-offset-9' : '' }}">
                            <div class="custom-button-container text-right">
                                <button class="btn btn-link" id="datatable-rencanakpibawahantaklangsung-refresh">
                                    <img src="{{ asset('assets/img/ic_refresh.png') }}">
                                </button>
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="rencanakpibawahantaklangsung-table" class="table table-striped" style="width:100%;">
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
                            <label class="col-sm-12 control-label text-left">Catatan Unapprove</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="CatatanUnapprove" rows="2" id="inputAlasanUnapprove" placeholder="Berikan catatan mengapa dokumen di unapprove" required></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-yellow" id="callButton">Ya</button>
                    <button type="button" class="btn btn-default btn-no noCallButton"  data-dismiss="modal">Tidak</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        $(function() {
            $('#rencanakpibawahantaklangsung-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route("backends.kpi.rencana.individu.bawahantaklangsung", request()->all()) !!}',
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
                    }, 
                ],
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
            });

            $('#datatable-rencanakpibawahantaklangsung-refresh').click(function() {
                $('#rencanakpibawahantaklangsung-table').DataTable().ajax.reload();
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
                    window.location.href = baseurl + "/kpi/rencana/bawahantaklangsung/" + id;
                }
            });

            $('#approve').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.rencana.approve') }}";
                var modalTitle = 'Approve Rencana KPI Individu';
                var modalContent = 'Apakah anda yakin untuk melakukan APPROVED Rencana KPI bawahan Anda?';
                $('#formgroup-alasan').hide();
                callAction(form, checked, action, modalTitle, modalContent);
            });

            $('#unapprove').click(function() {
                var form = $('#modalform');
                var checked = $(':checkbox:checked');
                var action = "{{ route('backends.kpi.rencana.unapprove') }}";
                var modalTitle = 'Unapprove Rencana KPI';
                var modalContent = 'Apakah anda yakin untuk melakukan UNAPPROVED Rencana KPI bawahan anda?';
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
                alert('Silakan pilih rencana terlebih dahulu.');
            } else {
                form.attr('action', action);
                checked.each(function(i) {
                    if($(this).val() !== 'on') {
                        form.append('<input type="hidden" name="id[]" value="'+ $(this).val() + '" class="appendedInput">')
                    }
                });
                $('#actionModalTitle').html(title);
                $('#actionModalContent').html(content);
                $('#actionConfirmModal').modal({backdrop: 'static', keyboard: false})  ;
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