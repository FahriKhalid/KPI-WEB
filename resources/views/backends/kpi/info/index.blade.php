@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				@include('vendor.flash.message')
				<div class="panel panel-default panel-box">
					<div class="panel-body">
						<div class="col-sm-12">
							<div class="panel-title-box">Data Info KPI</div>
						</div>
						<div class="col-sm-9">
							<div class="custom-button-container">
								<a href="{{ route('backend.kpi.create') }}">
									<button class="btn btn-link">
										<img src="{{ asset('assets/img/ic_add.png') }}"> Tambah Data
									</button>
								</a>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="custom-button-container text-right">
								<button class="btn btn-link" id="datatable-infokpi-refresh" data-toggle="tooltip" data-placement="top" title="Refresh data">
									<img src="{{ asset('assets/img/ic_refresh.png') }}">
								</button>
							</div>
						</div>
						<div class="margin-min-15">
							<table id="infokpi-table" class="table table-striped" width="100%">
						        <thead>
						            <tr>
						                <th>Judul</th>
										<th width="10%">Gambar</th>
						                <th width="40%">Informasi</th>
						                <th>Tanggal Publish</th>
						                <th>Tanggal berakhir</th>
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
	<div class="modal fade modal-notification" id="infoDeleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Hapus data info KPI?</h4>
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
            $('#infokpi-table').DataTable({
                processing: true,
                serverSide: true,
                // scrollX: true,
                ajax: '{!! route("backend.kpi.info") !!}',
                columns: [
                    { data: 'Judul', name: 'Judul' },
                    { data: 'Gambar', name: 'Gambar', sortable:false },
					{ data: 'Informasi', name: 'Informasi' },
                    { data: 'Tanggal_publish', name: 'Tanggal_publish' },
                    { data: 'Tanggal_berakhir', name: 'Tanggal_berakhir' },
                    { data: 'Aksi', name: 'Aksi', sortable: false }
                ]
            });
        });

        $('#datatable-infokpi-refresh').click(function() {
            $('#infokpi-table').DataTable().ajax.reload();
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