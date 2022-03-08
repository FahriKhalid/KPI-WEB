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
							<div class="panel-title-box">Data Rencana  KPI Individu</div>
						</div>
						<div class="col-sm-9">
							<div class="custom-button-container">
								<a href="{{ route('kpi.rencanaindividu.create') }}">
									<button class="btn btn-link">
										<img src="{{ asset('assets/img/ic_add.png') }}"> Add
									</button>
								</a>
								<a href="#">
									<button class="btn btn-link">
										<img src="{{ asset('assets/img/ic_update.png') }}"> Update
									</button>
								</a>
								<a href="#">
									<button class="btn btn-link">
										<img src="{{ asset('assets/img/ic_add.png') }}" class="rotate-30"> Cancel
									</button>
								</a>
								<a href="#">
									<button class="btn btn-link">
										<img src="{{ asset('assets/img/ic_confirm.png') }}"> Register
									</button>
								</a>
								<a href="#">
									<button class="btn btn-link">
										<img src="{{ asset('assets/img/ic_confirm.png') }}"> Unregister
									</button>
								</a>
								<a href="#">
									<button class="btn btn-link">
										<img src="{{ asset('assets/img/ic_edit.png') }}"> Refisi Target
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
						                <th>Tahun</th>
						                <th>NPK</th>
						                <th>Nama Karyawan</th>
						                <th>Grade</th>
						                <th>Jabatan</th>
						                <th>Kode Unit Kerja</th>
						                <th>Unit Kerja</th>
						                <th>Aksi</th>
						            </tr>
						        </thead>
						        <tbody>
						        	@for($i = 1; $i <= 9; $i++)
						            <tr>
						            	<td><input type="checkbox" id="checkItem"></td>
						                <td>{{ 'Draft' }}</td>
						                <td>{{ '201'.$i }}</td>
						                <td>{{ 'NPK00'.$i }}</td>
						                <td>{{ 'Nama '.$i }}</td>
						                <td>{{ 'IV'.$i }}</td>
						                <td>{{ 'Jabatan '.$i }}</td>
						                <td>{{ 'KD00'.$i }}</td>
						                <td>{{ 'Unit Kerja '.$i }}</td>
						                <td>
						                	<a data-toggle="modal" id="edit" href="#myModal" class="btn btn-xs btn-info">
												<i class="fa fa-info-circle"></i>
											</a>
											<a data-toggle="modal" href="{{ route('kpi.rencanaindividu.edit') }}" class="btn btn-xs btn-warning">
												<i class="fa fa-edit"></i>
											</a>
											<a href="#" class="btn btn-xs btn-danger">
												<i class="fa fa-trash-o"></i>
											</a>
						                </td>
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
	<!--<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-no" data-dismiss="modal">Tidak</button>
					<button type="button" class="btn btn-default btn-yellow">Iya</button>
				</div>
			</div>
		</div>
	</div>-->
	<div class="modal fade modal-notification" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
	</div>
@endsection

@section('customjs')
	
@endsection