@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.pengaturan')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Data Periode</div>
                            @include('vendor.flash.message')
                        </div>
                        <div class="col-sm-9">
                            <div class="custom-button-container">
                                <a href="{{ route('backend.master.periodeRealisasi.create') }}">
                                    <button class="btn btn-link">
                                        <img src="{{ asset('assets/img/ic_add.png') }}"> Tambah periode
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="custom-button-container text-right">
                                <button class="btn btn-link" title="Refresh data" id="datatable-periode-refresh">
                                    <img src="{{ asset('assets/img/ic_refresh.png') }}">
                                </button>
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="periode-table" class="table table-striped" width="100%">
                                <thead>
                                <tr>
                                    <th>Tahun</th>
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
    <div class="modal fade modal-notification" id="periodeDeleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Hapus data periode?</h4>
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
            $('#periode-table').DataTable({
                processing: true,
                serverSide: true,
                // scrollX: true,
                ajax: '{!! route("backend.master.periodeRealisasi") !!}',
                columns: [
                    { data: 'Tahun', name: 'Tahun'},
                    { data: 'Aksi', name: 'Aksi', sortable: false}
                ]
            });
        });

        $('#datatable-periode-refresh').click(function() {
            $('#periode-table').DataTable().ajax.reload();
        });

        $('#modal-confirm-delete-button').click(function()
        {
            var url = $(this).data('url');
            var token = '{{ csrf_token() }}';
            var data = {_method: 'delete', _token: token};
            $.ajax({
                url: url,
                type: 'post',
                data: data,
                success: function(result) {
                    // document.location.reload(true);
                },
                error: function(xhr) {
                    alert('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText);
                }
            });
        });
    </script>
@endsection