@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.master')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Data Jabatan</div>
                        </div>
                        <div class="col-sm-9">
                            <div class="custom-button-container">
                                <a href="{{ route('backend.master.jabatan.create') }}">
                                    <button class="btn btn-link">
                                        <img src="{{ asset('assets/img/ic_add.png') }}"> Tambah Jabatan
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="custom-button-container text-right">
                                <button class="btn btn-link" title="Refresh data" id="datatable-jabatan-refresh">
                                    <img src="{{ asset('assets/img/ic_refresh.png') }}">
                                </button>
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="jabatan-table" class="table table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID Posisi</th>
                                        <th>Nama Posisi</th>
                                        <th>Position Abbreviation</th>
                                        <th>Kode Unit Kerja</th>
                                        <th>Nama Unit Kerja</th>
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
    <div class="modal fade modal-notification" id="jabatanDeleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Hapus data jabatan?</h4>
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
            $('#jabatan-table').DataTable({
                processing: true,
                serverSide: true,
                // scrollX: true,
                ajax: '{!! route("backend.master.jabatan") !!}',
                columns: [
                    { data: 'PositionID', name: 'PositionID'},
                    { data: 'PositionTitle', name: 'PositionTitle'},
                    { data: 'PositionAbbreviation', name: 'PositionAbbreviation' },
                    { data: 'KodeUnitKerja', name: 'KodeUnitKerja'},
                    { data: 'unitkerja.Deskripsi', name: 'unitkerja.Deskripsi'},
                    { data: 'Aksi', name: 'Aksi', sortable: false }
                ]
            });
        });

        $('#datatable-jabatan-refresh').click(function() {
            $('#jabatan-table').DataTable().ajax.reload();
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