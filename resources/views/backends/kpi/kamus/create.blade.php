@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <form class="form-horizontal" id="formId" method="post" action="{{ route('backend.kpi.kamus.store') }}">
                    {!! csrf_field() !!}
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="panel-title-box">Tambah Data Kamus KPI</div>
                            </div>
                            <div class="col-sm-11 col-sm-offset-1">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Kode Registrasi</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="KodeRegistrasi" id="KodeRegistrasi" class="form-control" placeholder="Kode Registrasi Kamus KPI" value="{{ old('KodeRegistrasi') }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Kode Unit Kerja</label>
                                    <div class="col-sm-7">
                                        {!! Form::select('KodeUnitKerja', ['' => '-- Pilih Kode Unit Kerja --'] + $data['costcenter'] , old('KodeUnitKerja'), ['class' => 'form-control']+['required']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Kelompok</label>
                                    <div class="col-sm-5">
                                        {!! Form::select('IDAspekKPI', $data['aspekKpi'], old('IDAspekKPI'), ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Sifat</label>
                                    <div class="col-sm-5">
                                        {!! Form::select('IDJenisAppraisal', $data['jenisAppraisal'], old('IDJenisAppraisal'), ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Persentase Realisasi</label>
                                    <div class="col-sm-5">
                                        {!! Form::select('IDPersentaseRealisasi', $data['persentaseRealisasi'], old('IDPersentaseRealisasi'), ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Satuan</label>
                                    <div class="col-sm-5">
                                        {!! Form::select('IDSatuan', $data['satuan'], old('IDSatuan'), ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Judul KPI</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="KPI" placeholder="Judul Kamus KPI" class="form-control" value="{{ old('KPI') }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Deskripsi</label>
                                    <div class="col-sm-7">
                                        <textarea rows="10" name="Deskripsi">{!! old('Deskripsi') !!}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Rumus</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="Rumus" placeholder="Rumus KPI (opsional)" class="form-control" value="{{ old('Rumus') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Periode Laporan</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="PeriodeLaporan" placeholder="Periode Laporan (opsional)" class="form-control" value="{{ old('PeriodeLaporan') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Keterangan</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="Keterangan" placeholder="Keterangan tambahan kamus KPI (opsional)" class="form-control" value="{{ old('Keterangan') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Indikator Hasil</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="IndikatorHasil" placeholder="Indikator hasil (opsional)" class="form-control" value="{{ old('IndikatorHasil') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Indikator Kinerja</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="IndikatorKinerja" placeholder="Indikator kinerja (opsional)" class="form-control" value="{{ old('IndikatorKinerja') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Sumber data</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="SumberData" placeholder="Sumber data (opsional)" class="form-control" value="{{ old('SumberData') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Jenis</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="Jenis" placeholder="Jenis (opsional)" class="form-control" value="{{ old('Jenis') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right save-container">
                        <button type="submit" onclick="" id="save" class="btn btn-default btn-blue">Simpan</button>
                        <a href="{{ route('backend.kpi.kamus') }}" type="button" class="btn btn-default btn-orange">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=h15ws19lzjua0cwwlldwgyhfy3bra8xhir36p9rtgxhkadn6"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
    <script>
        function toggleIcon(e) {
            $(e.target)
                .prev('.panel-heading')
                .find(".more-less")
                .toggleClass('fa-angle-right fa-angle-down');
        }
        $('.panel-group').on('hidden.bs.collapse', toggleIcon);
        $('.panel-group').on('shown.bs.collapse', toggleIcon);
        $(function(){
            $('#save').one('click', function() {  
                $(this).attr('disabled','disabled');
                $('#formId').submit();
            });
        });
    </script>
        <style>
        .mce-edit-area{
            border-right: 1px solid #CCC!important;
        }
        </style>
@endsection