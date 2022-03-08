@extends('layouts.app')

@section('submenu')
	@include('layouts.submenu.rencana')
@endsection

@section('content')
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
								<div class="panel-title-box">Data Karyawan</div>
							</div>
							<div class="col-sm-11 col-sm-offset-1">
								<div class="form-group">
									<label class="col-sm-2 control-label">Tahun</label>
									<div class="col-sm-7">
										<select class="form-control">
											<option selected="selected">2017</option>
											<option>2016</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">NPK</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" placeholder="NPK" value="1104040" disabled="disabled">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Nama Karyawan</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" placeholder="Nama Karyawan" value="Kurniawan Dwi" disabled="disabled">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Grade</label>
									<div class="col-sm-3">
										<input type="text" class="form-control" placeholder="Grade" value="IV" disabled="disabled">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Jabatan</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" placeholder="Jabatan" value="Kepala Seksi Layanan Telekomunikasi" disabled="disabled">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Kode Unit Kerja</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" placeholder="Kode Unit Kerja" value="D00931319" disabled="disabled">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Unit Kerja</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" placeholder="Unit Kerja" value="DEPARTEMEN LAYANAN TEKNOLOGI INFORMASI" disabled="disabled">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Keterangan</label>
									<div class="col-sm-7">
										<textarea class="form-control" rows="6"></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<div class="panel panel-default panel-box panel-create panel-create-50">
						<div class="panel-body">
							<div class="col-sm-12">
								<div class="panel-title-box">Atasan Langsung</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
									<label class="col-sm-12 control-label text-left">NPK</label>
									<div class="col-sm-12">
										<input type="text" class="form-control" placeholder="NPK" value="12002312" disabled="disabled">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-12 control-label text-left">Nama</label>
									<div class="col-sm-12">
										<input type="text" class="form-control" placeholder="Nama" value="Lewis Perkins" disabled="disabled">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-12 control-label text-left">Jabatan</label>
									<div class="col-sm-12">
										<input type="text" class="form-control" placeholder="Jabatan" value="Direktur 1" disabled="disabled">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6">
					<div class="panel panel-default panel-box panel-create panel-create-50">
						<div class="panel-body">
							<div class="col-sm-12">
								<div class="panel-title-box">Atasan Berikutnya</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
									<label class="col-sm-12 control-label text-left">NPK</label>
									<div class="col-sm-12">
										<input type="text" class="form-control" placeholder="NPK" value="12002312" disabled="disabled">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-12 control-label text-left">Nama</label>
									<div class="col-sm-12">
										<input type="text" class="form-control" placeholder="Nama" value="Noah Keller" disabled="disabled">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-12 control-label text-left">Jabatan</label>
									<div class="col-sm-12">
										<input type="text" class="form-control" placeholder="Jabatan" value="Direktur 2" disabled="disabled">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="text-right save-container">
						<button type="submit" class="btn btn-default btn-blue">Simpan</button>
						<button type="button" class="btn btn-default btn-orange">Batal</button>
					</div>
				</div>
			</div>
		</div>
	</form>
@endsection

@section('customjs')
	
@endsection