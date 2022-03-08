@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.pengaturan')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <form class="form-horizontal" method="post" action="{{ route('validationmatrix.store') }}">
                    {!! csrf_field() !!}
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="panel-title-box">Tambah Matriks Validasi</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Unit Kerja</label>
                                <div class="col-sm-5">
                                    <select class="form-control selectbox" name="KodeUnitKerja" id="KodeUnitKerja">
                                        <option value="">Pilih Kode Unit Kerja</option>
                                        @foreach($data['unitkerja'] as $unitkerja)
                                            <option value="{{ $unitkerja->CostCenter }}" {{ (old('KodeUnitKerja') == $unitkerja->CostCenter) ? 'selected' : null }}>{{ $unitkerja->CostCenter }} - {{ $unitkerja->Deskripsi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Unit Kerja Penilai</label>
                                <div class="col-sm-5">
                                    <select class="form-control selectbox" name="KodeUnitKerjaPenilai" id="KodeUnitKerjaPenilai">
                                        <option value="">Pilih Kode Unit Kerja Penilai</option>
                                        @foreach($data['unitkerja'] as $unitkerja)
                                            <option value="{{ $unitkerja->CostCenter }}" {{ (old('KodeUnitKerja') == $unitkerja->CostCenter) ? 'selected' : null }}>{{ $unitkerja->CostCenter }} - {{ $unitkerja->Deskripsi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Keterangan</label>
                                <div class="col-sm-5">
                                    <input type="text" name="Keterangan" value="{{ old('Keterangan') }}" class="form-control" placeholder="Keterangan (opsional)">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right save-container">
                        <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                        <a href="{{ route('validationmatrix.index') }}" type="button" class="btn btn-default btn-orange">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('customjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
    <script>
        $('.selectbox').select2();
    </script>
@endsection