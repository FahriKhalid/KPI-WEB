@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Data Kompetensi</div>
                        </div>
                        
                        <div class="col-sm-9">
                            <div class="custom-button-container">
                                <a href="{{ route('backend.master.kompetensi.create') }}">
                                    <button class="btn btn-link">
                                        <img src="{{ asset('assets/img/ic_add.png') }}"> Tambah Kompetensi
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="custom-button-container text-right">
                                <button class="btn btn-link" id="datatable-kompetensi-refresh" data-toggle="tooltip" data-placement="top" title="Refresh data">
                                    <img src="{{ asset('assets/img/ic_refresh.png') }}">
                                </button>
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="kompetensi-table" class="table table-striped" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>PositionID</th>
                                    <th>Keterangan</th>
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
    <div class="modal fade modal-notification" id="kompetensiDeleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Hapus data Kompetensi?</h4>
                </div>
                <div class="modal-body">
                    Warning! Data yang dihapus tidak dapat kembali.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-no" data-dismiss="modal">Tidak</button>
                    <button type="button" id="modal-confirm-delete-button" data-url="" class="btn btn-default btn-danger delete">Iya</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        $(function() {
            $('#kompetensi-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{!! route("backend.master.kompetensi.data") !!}",
                    "type": "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    { data: 'ID', name: 'ID' },
                    { data: 'PositionID', name: 'PositionID'},
                    { data: 'Keterangan', name: 'Keterangan'},
                    { data: 'Aksi', name: 'Aksi', sortable: false }
                ]
            });
        });

        $('#datatable-kompetensi-refresh').click(function() {
            $('#kompetensi-table').DataTable().ajax.reload();
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