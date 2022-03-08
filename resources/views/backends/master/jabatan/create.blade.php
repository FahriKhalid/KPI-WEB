@extends('layouts.app')

@section('submenu')
	@include('layouts.submenu.master')
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				@include('vendor.flash.message')
				<form id="create-user-form" class="form-horizontal" method="post" action="{{ route('backend.master.jabatan.store') }}">
					{!! csrf_field() !!}
					<div class="panel panel-default panel-box panel-create">
						<div class="panel-body">
							<div class="col-sm-12">
								<div class="panel-title-box">Tambah Jabatan</div>
							</div>
							<div class="col-sm-11 col-sm-offset-1">
								<div class="form-group">
									<label class="col-sm-2 control-label">ID Posisi</label>
									<div class="col-sm-7">
										<input type="text" name="PositionID" class="form-control" placeholder="ID Posisi" value="{{ old('PositionID') }}" required>
									</div>
								</div>
								<div class="form-group" id="app">
									<label class="col-sm-2 control-label">Judul Posisi</label>
									<div class="col-sm-7">
										<input type="text" name="PositionTitle" class="form-control" placeholder="Judul Posisi" value="{{ old('PositionTitle') }}" required>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Posisi Abbreviation</label>
									<div class="col-sm-7">
										<input type="text" name="PositionAbbreviation" class="form-control" placeholder="Posisi Abbreviation" value="{{ old('PositionAbbreviation') }}" required>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Kode Unit Kerja</label>
									<div class="col-sm-7">
										{!! Form::select('KodeUnitKerja', ['' => '-- Pilih Kode Unit Kerja --'] + $data['costcenter'], old('KodeUnitKerja'), ['class' => 'form-control']) !!}
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Status Aktif</label>
									<div class="col-sm-7">
                                        <ul class="container-check">
                                            <li>
                                                <input type="radio" id="f-option" name="StatusAktif" value="1" checked="checked">
                                                <label for="f-option">Aktif</label>
                                                <div class="check"></div>
                                            </li>
                                            <li>
                                                <input type="radio" id="s-option" name="StatusAktif" value="0">
                                                <label for="s-option">Non Aktif</label>
                                                <div class="check"><div class="inside"></div></div>
                                            </li>
                                        </ul>
                                    </div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Keterangan</label>
									<div class="col-sm-7">
										<input type="text" name="Keterangan" class="form-control" placeholder="Keterangan" value="{{ old('Keterangan') }}" >
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="text-right save-container">
						<button type="submit" id="save" class="btn btn-default btn-blue">Simpan</button>
						<a href="{{ route('backend.master.jabatan') }}" type="button" class="btn btn-default btn-orange">Batal</a>
					</div>
				</form>
			</div>
		</div>
	</div>


@endsection

@section('customjs')
	<script src="https://unpkg.com/vue/dist/vue.js"></script>
	<script type="text/javascript">
		Vue.config.devtools = true;
	</script>

<script type="text/javascript">
		$(function(){
            $('#save').one('click', function() {  
                $(this).attr('disabled','disabled');
                $('#create-user-form').submit();
            });
        });
</script>

@endsection