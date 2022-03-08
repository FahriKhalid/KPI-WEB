@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Data Periode Aktif</div>
                        </div>
                        <div class="col-sm-9">
                            <div class="custom-button-container">
                                <a href="{{ route('backend.master.periodeaktif.create') }}">
                                    <button class="btn btn-link">
                                        <img src="{{ asset('assets/img/ic_add.png') }}"> Tambah Periode Aktif
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="custom-button-container text-right">
                                <button class="btn btn-link" id="datatable-periodeaktif-refresh" data-toggle="tooltip" data-placement="top" title="Refresh data">
                                    <img src="{{ asset('assets/img/ic_refresh.png') }}">
                                </button>
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="periodeaktif-table" class="table table-striped" width="100%">
                                <thead>
                                <tr>
                                    <th>Tahun</th>
                                    <th>Nama Periode Aktif</th>
                                    <th>Jenis Periode</th>
                                    <th>Periode KPI</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-notification" id="periodeaktifDeleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Hapus Data Tahun Periode Aktif?</h4>
                </div>
                <div class="modal-body">
                    Perhatian! Aksi ini akan menghapus seluruh data periode aktif pada satu tahun periode. Anda yakin?
                </div>
                <div class="modal-footer">
                    <button type="button" id="modal-confirm-delete-button" data-url="" class="btn btn-default btn-danger delete">Ya</button>
                    <button type="button" class="btn btn-default btn-no" data-dismiss="modal">Tidak</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        $(function() {
            $('#periodeaktif-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{!! route("backend.master.periodeaktif") !!}",
                    "type": "GET",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    { data: 'Tahun', name: 'Tahun'},
                    { data: 'NamaPeriode', name: 'NamaPeriode'},
                    { data: 'jenisperiode.JenisPeriode', name: 'jenisperiode.JenisPeriode'},
                    { data: 'jenisperiode.NamaPeriodeKPI', name: 'jenisperiode.NamaPeriodeKPI'},
                    { data: 'StartDate', name: 'StartDate', defaultContent: '-'},
                    { data: 'EndDate', name: 'EndDate', defaultContent: '-'},
//                    { data: 'Aktif', name: 'Aktif'},
                    { data: 'Aksi', name: 'Aksi', sortable: false }
                ]
            });
        });

        $('#datatable-periodeaktif-refresh').click(function() {
            $('#periodeaktif-table').DataTable().ajax.reload();
        });

        $('#modal-confirm-delete-button').click(function()
        {
            $(this).attr('disabled','disabled');
            var url = $(this).data('url');
            var token = '{{ csrf_token() }}';
            var data = {_method: 'delete', _token: token};
            $.ajax({
                url: url,
                type: 'post',
                data: data,
                success: function(result) {
                    document.location.reload(true);
                },
                error: function(xhr) {
                    alert('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText);
                }
            });
        });
    </script>
@endsection