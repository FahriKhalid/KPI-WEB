@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Data Unit Kerja</div>
                        </div>
                        
                        <div class="col-sm-9">
                            <div class="custom-button-container">
                                <a href="{{ route('backend.master.unitkerja.create') }}">
                                    <button class="btn btn-link">
                                        <img src="{{ asset('assets/img/ic_add.png') }}"> Tambah Unit Kerja
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="custom-button-container text-right">
                                <button class="btn btn-link" id="datatable-unitkerja-refresh" data-toggle="tooltip" data-placement="top" title="Refresh data">
                                    <img src="{{ asset('assets/img/ic_refresh.png') }}">
                                </button>
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="unitkerja-table" class="table table-striped" width="100%">
                                <thead>
                                <tr>
                                    <th>Kode Unit Kerja</th>
                                    <th>Nama Unit Kerja</th>
                                    <th>Keterangan</th>
                                    <th>Aktif</th>
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
    <div class="modal fade modal-notification" id="unitkerjaDeleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Hapus data Unit Kerja?</h4>
                </div>
                <div class="modal-body">
                    Warning! Data yang dihapus tidak dapat kembali.
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
            $('#unitkerja-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{!! route("backend.master.unitkerja.data") !!}",
                    "type": "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    { data: 'CostCenter', name: 'CostCenter' },
                    { data: 'Deskripsi', name: 'Deskripsi'},
                    { data: 'Keterangan', name: 'Keterangan'},
                    { data: 'Aktif', name: 'Aktif'},
//                    { data: 'Field1', name: 'Field1'},
//                    { data: 'Field2', name: 'Field2'},
//                    { data: 'Field3', name: 'Field3'},
//                    { data: 'Field4', name: 'Field4'},
//                    { data: 'Field5', name: 'Field5'},
                    { data: 'Aksi', name: 'Aksi', sortable: false }
                ]
            });
        });

        $('#datatable-unitkerja-refresh').click(function() {
            $('#unitkerja-table').DataTable().ajax.reload();
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