@extends('layouts.app')

@section('submenu')
	@include('layouts.submenu.rencana')
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 margin-top-15">
				<ul class="kpi-menu">
					<li class="active">
						<a href="{{ route('kpi.rencanaindividu.edit') }}">Rencana KPI</a>
					</li>
					<li>
						<a href="{{ route('kpi.penurunanindividu.edit') }}">Penurunan KPI</a>
					</li>
					<li>
						<a href="{{ route('kpi.dokumenindividu.edit') }}">Dokumen</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<form class="form-horizontal">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-sm-12 margin-top-30">
					<!--<div class="alert alert-danger alert-dismissible" role="alert">
	                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><	span aria-hidden="true">&times;</span></button>
	                    Username atau password anda salah
	                </div>-->
					<div class="panel panel-default panel-box panel-create">
						<div class="panel-body">
							<div class="col-sm-12">
								<div class="panel-title-box no-border">Data Karyawan</div>
							</div>
							<div class="col-sm-12">
								<div class="border-bottom-container margin-bottom-15">
									<div class="table-responsive">
										<table class="table table-no-border">
											<tr>
												<th>Tahun</th>
												<th>NPK</th>
												<th>Nama Karyawan</th>
											</tr>
											<tr>
												<td>2017</td>
												<td>1092139</td>
												<td>Jason Francis</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="panel-title-box no-border">Penurunan KPI</div>
							</div>
							<div class="col-sm-11 col-sm-offset-1 margin-bottom-15">
								<div class="form-group">
									<label class="col-sm-2 control-label">KPI</label>
									<div class="col-sm-4">
										<select class="form-control">
											<option selected="selected">KPI</option>
											<option>Non KPI</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Bawahan</label>
									<div class="col-sm-4">
										<select class="form-control">
											<option selected="selected">Bawahan 1</option>
											<option>Bawahan 2</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Presentase KRA</label>
									<div class="col-sm-4">
										<select class="form-control">
											<option selected="selected">Presentase KRA 1</option>
											<option>Presentase KRA 2</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Keterangan</label>
									<div class="col-sm-6">
										<textarea class="form-control" rows="3"></textarea>
									</div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="border-bottom-container margin-bottom-15"></div>
							</div>
							<div class="col-sm-12">
								<div class="panel-title-box no-border no-margin-bottom">Penurun KPI</div>
							</div>
							<div class="col-sm-9">
								<div class="custom-button-container">
									<a href="#">
										<button class="btn btn-link">
											<img src="{{ asset('assets/img/ic_update.png') }}"> Simpan
										</button>
									</a>
									<a href="#">
										<button class="btn btn-link">
											<img src="{{ asset('assets/img/ic_edit.png') }}"> Ubah
										</button>
									</a>
									<a href="#">
										<button class="btn btn-link">
											<img src="{{ asset('assets/img/ic_add.png') }}" class="rotate-30"> Hapus
										</button>
									</a>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="custom-button-container text-right">
									<button class="btn btn-link">
										<img src="{{ asset('assets/img/ic_refresh.png') }}">
									</button>
								</div>
							</div>
							<div class="margin-min-15">
								<table id="table-data" class="table table-striped" width="100%">
							        <thead>
							            <tr>
							            	<th><input type="checkbox" id="checkAll"></th>
							                <th>KPI</th>
							                <th>Satuan</th>
							                <th>Target</th>
							                <th>Bobot</th>
							                <th>NPK Bawahan</th>
							                <th>Jenis Cascade</th>
							                <th>Presentase KRA</th>
							                <th>Keterangan</th>
							            </tr>
							        </thead>
							        <tbody>
							        	@for($i = 1; $i <= 9; $i++)
							            <tr>
							            	<td><input type="checkbox" id="checkItem"></td>
							                <td>KPI</td>
							                <td>Satuan</td>
							                <td>Target</td>
							                <td>Bobot</td>
							                <td>NPK Bawahan</td>
							                <td>Jenis Cascade</td>
							                <td>Presentase KRA</td>
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
	</form>
@endsection

@section('customjs')
	
@endsection