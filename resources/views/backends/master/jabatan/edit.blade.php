@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.master')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <form id="edit-jabatan-form" class="form-horizontal" method="post" action="{{ route('backend.master.jabatan.update', ['id' => $data['jabatan']->ID]) }}">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="put">
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="panel-title-box">Edit Jabatan</div>
                            </div>
                            <div class="col-sm-11 col-sm-offset-1">
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Id posisi</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="PositionID" placeholder="Position ID" class="form-control" value="{{ $data['jabatan']->PositionID }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Judul Posisi</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="PositionTitle" class="form-control" placeholder="Position Title" value="{{ $data['jabatan']->PositionTitle }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Posisi Abbreviation</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="PositionAbbreviation" class="form-control" placeholder="Posisi Abbreviation" value="{{ $data['jabatan']->PositionAbbreviation }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Kode Unit Kerja</label>
                                    <div class="col-sm-7">
                                        {!! Form::select('KodeUnitKerja', ['' => '-- Pilih Kode Unit Kerja --'] + $data['costcenter'], $data['jabatan']->KodeUnitKerja, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Status Aktif</label>
                                    <div class="col-sm-7">
                                        <ul class="container-check">
                                            <li>
                                                <input type="radio" id="f-option" name="StatusAktif" value="1" {{$data['jabatan']->StatusAktif ==1?"checked":""}}>
                                                <label for="f-option">Aktif</label>
                                                <div class="check"></div>
                                            </li>
                                            <li>
                                                <input type="radio" id="s-option" name="StatusAktif" value="0" {{$data['jabatan']->StatusAktif ==0?"checked":""}}>
                                                <label for="s-option">Non Aktif</label>
                                                <div class="check"><div class="inside"></div></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Keterangan</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="Keterangan" class="form-control" placeholder="Keterangan" value="{{ $data['jabatan']->Keterangan }}" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right save-container">
                        <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                        <a href="{{ route('backend.master.jabatan') }}" type="button" class="btn btn-default btn-orange">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('customjs')

@endsection