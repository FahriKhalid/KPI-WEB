@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Artikel Narasi Home</div>
                        </div>
                        <div class="col-sm-12 margin-top-15">

                        </div>
                        <div class="col-sm-9">
                            <div class="custom-button-container">
                                <a href="{{ route('artikel.create') }}">
                                    <button class="btn btn-link">
                                        <img src="{{ asset('assets/img/ic_add.png') }}"> Tambah Data
                                    </button>
                                </a>

                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="custom-button-container text-right">
                                <button class="btn btn-link" id="datatable-artikel-refresh" data-toggle="tooltip" data-placement="top" title="Refresh data">
                                    <img src="{{ asset('assets/img/ic_refresh.png') }}">
                                </button>
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="artikel-table" class="table table-striped" style="width:1500px">
                                <thead>
                                    <tr>
                                        <th>Aktif</th>
                                        {{--<th>Title</th>--}}
                                        <th>Aksi</th>
                                        <th>Content</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-notification" id="artikelDeleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Hapus data artikel ?</h4>
                </div>
                <div class="modal-body">
                    Peringatan! Data yang dihapus tidak akan bisa dikembalikan.
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
            $('#artikel-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route("artikel") !!}',
                columns: [
                    { data: 'Aktif', name: 'Aktif'},
//                    { data: 'Title', status:'Title'},
                    { data: 'Aksi', orderable:false},
                    { data: 'Content', name: 'Content' }
                ]
            });
        });

        $('#datatable-artikel-refresh').click(function() {
            $('#artikel-table').DataTable().ajax.reload();
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
                    document.location.reload(true);
                },
                error: function(xhr) {
                    alert('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText);
                }
            });
        });
    </script>
@endsection