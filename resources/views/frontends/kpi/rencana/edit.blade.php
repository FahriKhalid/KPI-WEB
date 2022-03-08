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
												<th>Grade</th>
												<th>Jabatan</th>
												<th>Kode Unit Kerja</th>
												<th>Unit Kerja</th>
											</tr>
											<tr>
												<td>2017</td>
												<td>1092139</td>
												<td>Jason Francis</td>
												<td>IV</td>
												<td>Kepala Seksi Layanan Telekomnikasi</td>
												<td>D0093123</td>
												<td>Dept. Teknologi Informasi</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="panel-title-box no-border">Atasan Langsung</div>
							</div>
							<div class="col-sm-12">
								<div class="border-bottom-container margin-bottom-15">
									<div class="table-responsive">
										<table class="table table-no-border">
											<tr>
												<th>NPK</th>
												<th>Nama Karyawan</th>
												<th>Jabatan</th>
											</tr>
											<tr>
												<td>1092139</td>
												<td>Jason Francis</td>
												<td>Kepala Seksi Layanan Telekomnikasi</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="panel-title-box no-border">Atasan Langsung</div>
							</div>
							<div class="col-sm-12">
								<div class="border-bottom-container margin-bottom-15">
									<div class="table-responsive">
										<table class="table table-no-border">
											<tr>
												<th>NPK</th>
												<th>Nama Karyawan</th>
												<th>Jabatan</th>
											</tr>
											<tr>
												<td>1092139</td>
												<td>Jason Francis</td>
												<td>Kepala Seksi Layanan Telekomnikasi</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							<div class="col-sm-11 col-sm-offset-1 margin-top-30">
								<div class="form-group">
									<label class="col-sm-2 control-label">Aspek</label>
									<div class="col-sm-4">
										<select class="form-control">
											<option selected="selected">Strategi</option>
											<option>Non Strategi</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Jenis Appraisat</label>
									<div class="col-sm-4">
										<select class="form-control">
											<option selected="selected">Akumulatif</option>
											<option>Non Akumulatif</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Presentase Realisasit</label>
									<div class="col-sm-4">
										<select class="form-control">
											<option selected="selected">Higher Better</option>
											<option>Non Higher Better</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">KRA</label>
									<div class="col-sm-6">
										<textarea class="form-control" rows="3"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">KRA</label>
									<div class="col-sm-7">
										<textarea class="form-control" rows="3"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Jenis Periode</label>
									<div class="col-sm-4">
										<select class="form-control">
											<option selected="selected">Tribun</option>
											<option>Non Tribun</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Target TW 1</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" placeholder="Target TW 1">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Target TW 2</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" placeholder="Target TW 2">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Target TW 3</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" placeholder="Target TW 3">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Satuan</label>
									<div class="col-sm-4">
										<select class="form-control">
											<option selected="selected">Satuan</option>
											<option>Non Satuan</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Keterangan</label>
									<div class="col-sm-7">
										<textarea class="form-control" rows="3"></textarea>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-7">
										<button type="submit" class="btn btn-default btn-blue">Add</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-default panel-box panel-create">
						<div class="panel-body">
							<div class="col-sm-12">
								<div class="panel-title-box no-border no-margin-bottom">Detail KPI</div>
							</div>
							<div class="col-sm-9">
								<div class="custom-button-container">
									<a href="#">
										<button class="btn btn-link">
											<img src="{{ asset('assets/img/ic_update.png') }}"> Update
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
										<img src="{{ asset('assets/img/ic_print.png') }}"> Print
									</button>
								</div>
							</div>
							<div class="margin-min-15">
								<table id="table-data" class="table table-striped" width="100%">
							        <thead>
							            <tr>
							            	<th><input type="checkbox" id="checkAll"></th>
							                <th>Aspek KPI</th>
							                <th>Jenis Appraisal</th>
							                <th>Presentasi Realisasi</th>
							                <th>KRA</th>
							                <th>KPI</th>
							                <th>Satuan</th>
							            </tr>
							        </thead>
							        <tbody>
							        	@for($i = 1; $i <= 9; $i++)
							            <tr>
							            	<td><input type="checkbox" id="checkItem"></td>
							                <td>{{ 'Aspek KPI' }}</td>
							                <td>{{ 'Jenis Appraisal'.$i }}</td>
							                <td>{{ 'Presentasi Realisasi'.$i }}</td>
							                <td>{{ 'KRA '.$i }}</td>
							                <td>{{ 'KPI'.$i }}</td>
							                <td>{{ 'Satuan '.$i }}</td>
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