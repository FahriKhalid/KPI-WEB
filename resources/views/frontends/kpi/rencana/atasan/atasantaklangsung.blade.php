@extends('layouts.app')

@section('submenu')
	@include('layouts.submenu.rencana')
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<!--<div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Username atau password anda salah
                </div>-->
				<div class="panel panel-default panel-box">
					<div class="panel-body">
						<div class="col-sm-12">
							<div class="panel-title-box">KPI Bawahan</div>
						</div>
						<div class="col-sm-9">
							<div class="custom-button-container">
								<a href="{{ route('kpi.rencanaindividu.create') }}">
									<button class="btn btn-link">
										<img src="{{ asset('assets/img/ic_confirm.png') }}"> Confirm
									</button>
								</a>
								<a href="#">
									<button class="btn btn-link">
										<img src="{{ asset('assets/img/ic_unconfirm.png') }}"> Unconfirm
									</button>
								</a>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="custom-button-container text-right">
								<button class="btn btn-link">
									<img src="{{ asset('assets/img/ic_refresh.png') }}">
								</button>
								<button class="btn btn-link">
									<img src="{{ asset('assets/img/ic_filter.png') }}">
								</button>
							</div>
						</div>
						<div class="margin-min-15">
							<table id="table-data" class="table table-striped" width="100%">
						        <thead>
						            <tr>
						            	<th><input type="checkbox" id="checkAll"></th>
						                <th>Status</th>
						                <th>NPK Bawahan</th>
						                <th>Nama Bawahan Langsung</th>
						                <th>Periode</th>
						                <th>Kode Unit Kerja</th>
						                <th>Keterangan</th>
						            </tr>
						        </thead>
						        <tbody>
						        	@for($i = 1; $i <= 9; $i++)
						            <tr>
						            	<td><input type="checkbox" id="checkItem"></td>
						                <td>Status</td>
						                <td>NPK Bawahan</td>
						                <td>Nama Bawahan Langsung</td>
						                <td>Periode</td>
						                <td>Kode Unit Kerja</td>
						                <td>Keterangan</td>
						            </tr>
						            @endfor
						        </tbody>
						    </table>
					    </div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Modal title</h4>
				</div>
				<div class="modal-body">
					Lorem ipsum dolor sit amet, consectetur adipiscing elit.
					Fusce ultrices euismod lobortis.
					<form class="form-horizontal">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-2 control-label">Alasan</label>
							<div class="col-sm-10">
								<textarea rows="3" class="form-control"></textarea>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-no" data-dismiss="modal">Tidak</button>
					<button type="button" class="btn btn-default btn-yellow">Iya</button>
				</div>
			</div>
		</div>
	</div>
	<!--<div class="modal fade modal-notification" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body text-center">
					<img src="{{ asset('assets/img/ic_warning.png') }}" class="img-warning-modal">
					<h4 class="modal-title" id="myModalLabel">Modal title</h4>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ultrices euismod lobortis.
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ultrices euismod lobortis.
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ultrices euismod lobortis.
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-no" data-dismiss="modal">Tidak</button>
					<button type="button" class="btn btn-default btn-yellow">Iya</button>
				</div>
			</div>
		</div>
	</div>-->
@endsection

@section('customjs')
	<script type="text/javascript">
		$('#myModal').modal('show')
	</script>
@endsection