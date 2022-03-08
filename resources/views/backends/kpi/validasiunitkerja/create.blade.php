@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <form class="form-horizontal" method="post" action="{{ route('backends.realisasi.validasi.unitkerja.store') }}">
                    {!! csrf_field() !!}
                    <input type="hidden" name="IDKPIRealisasiHeader" value="{{ $data['headerrealisasi']->ID }}">
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="panel-title-box">Validasi Nilai KPI Unit Kerja</div>
                            </div>
                            <div class="col-sm-12">
                                <div class="border-bottom-container margin-bottom-15">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">Data Unit Kerja</div>
                                        <div class="panel-body">
                                            <ul class="list-group">
                                                <li class="list-group-item"><strong>Unit Kerja</strong><span class="pull-right">{{ $data['headerrealisasi']->karyawan->organization->position->unitkerja->Deskripsi }} ({{ $data['headerrealisasi']->karyawan->organization->position->unitkerja->CostCenter }})</span></li>
                                                <li class="list-group-item"><strong>Nama Manager</strong><span class="pull-right">{{ $data['headerrealisasi']->karyawan->NamaKaryawan }} ({{ $data['headerrealisasi']->karyawan->NPK }})</span></li>
                                                <li class="list-group-item"><strong>Nilai KPI</strong><span class="pull-right">{{ $data['headerrealisasi']->NilaiAkhir }}</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tahun</label>
                                <div class="col-sm-3">
                                    <input type="text" name="Tahun" class="form-control" value="{{ $data['headerrealisasi']->Tahun }}" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Periode</label>
                                <div class="col-sm-3">
                                    <input type="text" name="IDJenisPeriode" class="form-control" value="{{ $data['headerrealisasi']->jenisperiode->KodePeriode }}" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nilai Validasi Unit Kerja</label>
                                <div class="col-sm-3">
                                    <ul class="container-check">
                                        <li>
                                            <input type="radio" id="s-option" name="ValidasiUnitKerja" value="105">
                                            <label for="s-option">Sangat Puas (105%)</label>
                                            <div class="check"><div class="inside"></div></div>
                                        </li>
                                        <li>
                                            <input type="radio" id="f-option" name="ValidasiUnitKerja" value="100">
                                            <label for="f-option">Puas (100%)</label>
                                            <div class="check"></div>
                                        </li>
                                        <li>
                                            <input type="radio" id="x-option" name="ValidasiUnitKerja" value="95">
                                            <label for="x-option">Kurang Puas (95%)</label>
                                            <div class="check"></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-offset-2 save-container">
                                <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                                <a href="{{ route('backends.realisasi.validasi.unitkerja.index') }}" type="button" class="btn btn-default btn-orange">Batal</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection