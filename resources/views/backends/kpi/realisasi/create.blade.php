@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.rencana')
@endsection

@section('content')
    <form class="form-horizontal" action="{{ route('backends.kpi.realisasi.store') }}" method="post">
        {!! csrf_field() !!}
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 margin-top-30">
                    @include('vendor.flash.message')
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="panel-title-box">Data Karyawan</div>
                            </div>
                            <div class="col-sm-11 col-sm-offset-1">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Tahun</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="Tahun" value="{{ $data['approvedRencana']->Tahun }}" class="form-control" readonly>
                                        <input type="hidden" name="IDHeaderRencana" value="{{ $data['approvedRencana']->ID }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Periode</label>
                                    <div class="col-sm-3">
                                        <select name="KodePeriode" class="form-control">
                                            @foreach($data['periode'] as $periode)
                                                <option value="{{ $periode->ID }}">{{ $periode->jenisperiode->KodePeriode }} - {{ $periode->NamaPeriode }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">NPK</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="NPK" placeholder="NPK" value="{{ $data['user']->NPK }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Nama Karyawan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="NamaKaryawan" placeholder="Nama Karyawan" value="{{ $data['user']->karyawan->NamaKaryawan }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Grade</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="Grade" placeholder="Grade" value="{{ $data['user']->karyawan->organization->Grade }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Jabatan</label>
                                    <input type="hidden" name="IDMasterPosition" value="{{ $data['user']->karyawan->organization->PositionID }}">
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="Jabatan" placeholder="Jabatan" value="{{ $data['user']->karyawan->organization->position->PositionTitle }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Kode Unit Kerja</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="KodeUnitKerja" placeholder="Kode Unit Kerja" value="{{ $data['user']->karyawan->organization->position->KodeUnitKerja }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Unit Kerja</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="DeskripsiUnitKerja" placeholder="Unit Kerja" value="{{ $data['user']->karyawan->organization->position->unitkerja->Deskripsi }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Keterangan</label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" name="Keterangan" rows="6" >{{ old('Keterangan') }}</textarea>
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
                                @if(empty($data['atasanLangsung']))
                                    <div class="alert alert-warning alert-dismissible" id="alertdataatasanlangsung" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <strong>Perhatian!</strong> Atasan langsung Anda kosong. Silakan pilih atasan langsung anda.
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label class="col-sm-12 control-label text-left">NPK</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" name="NPKAtasanLangsung" id="npkatasanlangsung" placeholder="NPK" value="{{ (! empty(old('NPKAtasanLangsung'))) ? old('NPKAtasanLangsung') : ((! empty($data['atasanLangsung'])) ? $data['atasanLangsung']->NPK : null) }}">
                                        <span class="help-block">Ketik NPK untuk mencari data atasan.</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label text-left">Nama</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" name="NamaAtasanLangsung" id="namaatasanlangsung" placeholder="Nama" value="{{ (! empty(old('NamaAtasanLangsung'))) ? old('NamaAtasanLangsung') : ((! empty($data['atasanLangsung'])) ? $data['atasanLangsung']->NamaKaryawan : null) }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label text-left">Jabatan</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" name="JabatanAtasanLangsung" id="jabatanatasanlangsung" placeholder="Jabatan" value="{{ (! empty(old('JabatanAtasanLangsung'))) ? old('JabatanAtasanLangsung') : ((! empty($data['atasanLangsung'])) ? $data['atasanLangsung']->organization->position->PositionTitle : null) }}" readonly>
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
                                @if(empty($data['atasanLangsung']))
                                    <div class="alert alert-warning alert-dismissible" id="alertdataatasantaklangsung" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <strong>Perhatian!</strong> Atasan tak langsung Anda kosong. Silakan pilih atasan tak langsung anda.
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label class="col-sm-12 control-label text-left">NPK</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" name="NPKAtasanBerikutnya" id="npkatasantaklangsung" placeholder="NPK" value="{{ (! empty(old('NPKAtasanBerikutnya'))) ? old('NPKAtasanBerikutnya') : ((! empty($data['atasanTakLangsung'])) ? $data['atasanTakLangsung']->NPK : null) }}">
                                        <span class="help-block">Ketik NPK untuk mencari data atasan.</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label text-left">Nama</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" name="NamaAtasanBerikutnya" id="namaatasantaklangsung" placeholder="Nama" value="{{ (! empty(old('NamaAtasanBerikutnya'))) ? old('NamaAtasanBerikutnya') : ((! empty($data['atasanTakLangsung'])) ? $data['atasanTakLangsung']->NamaKaryawan : null) }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label text-left">Jabatan</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" name="JabatanAtasanBerikutnya" id="jabatanatasantaklangsung" placeholder="Jabatan" value="{{ (! empty(old('JabatanAtasanBerikutnya'))) ? old('JabatanAtasanBerikutnya') : ((! empty($data['atasanTakLangsung'])) ? $data['atasanTakLangsung']->organization->position->PositionTitle : null) }}" readonly>
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
                        <a href="{{ route('backends.kpi.realisasi.individu') }}" class="btn btn-default btn-orange">Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('customjs')
    <script src="{{ asset('assets/js/easyautocomplete/jquery.easy-autocomplete.min.js') }}"></script>
    <script>
        function buildOptions(attribute) {
            return options = {
                url: function(phrase) {
                    return "{{ url('') }}/master/karyawan/search/npk?generic=1&keyword=" + phrase;
                },

                getValue: "NPK",
                template: {
                    type: 'custom',
                    method: function(value, item) {
                        return value + ' - ' + item.NamaKaryawan;
                    }
                },
                list: {
                    onSelectItemEvent: function() {
                        var nama = $("#npk" + attribute).getSelectedItemData().NamaKaryawan;
                        var jabatan = $("#npk" + attribute).getSelectedItemData().organization.position.PositionTitle;
                        $("#nama" + attribute).val(nama).trigger("change");
                        $("#jabatan" + attribute).val(jabatan).trigger("change");
                    }
                },
                requestDelay: 250,
                placeholder: "Silakan ketik NPK atasan"
            };
        }

        $("#npkatasanlangsung").easyAutocomplete(buildOptions('atasanlangsung'));
        $("#npkatasantaklangsung").easyAutocomplete(buildOptions('atasantaklangsung'));
    </script>
@endsection
