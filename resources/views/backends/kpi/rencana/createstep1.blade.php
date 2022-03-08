@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.rencana')
@endsection

@section('content')
    <form class="form-horizontal" action="{{ route('backends.kpi.rencana.individu.store.step1') }}" method="post">
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
                                        <select name="Tahun" class="form-control">
                                            @foreach($data['periode'] as $periode)
                                                <option value="{{ $periode->Tahun }}" {{ (old('Tahun') == $periode->Tahun) ? 'selected' : '' }}>{{ $periode->Tahun }}</option>
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
                                        <input type="text" class="form-control" name="NamaKaryawan" placeholder="Nama Karyawan" value="{{ getInfoKaryawan($data['user']->karyawan->NPK) == null ? '-' : getInfoKaryawan($data['user']->karyawan->NPK)->NAME }}" readonly>
                                    </div>
                                </div>
                                {{-- untuk direksi --}}
                                @if($data['user']->IDRole == 8) 

                                @php
                                    $direksi = \DB::table("Ms_Direksi")->where("Npk", $data["user"]->NPK)->first(); 
                                @endphp

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Grade</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="Grade" placeholder="Grade" value="{{ $direksi->Grade }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Jabatan</label>
                                    <input type="hidden" name="IDMasterPosition" value="{{ $direksi->PositionID }}">
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="Jabatan" placeholder="Jabatan" value="{{ $direksi->Jabatan }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Kode Unit Kerja</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="KodeUnitKerja" placeholder="Kode Unit Kerja" value="{{ $direksi->KodeUnitKerja }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Unit Kerja</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="DeskripsiUnitKerja" placeholder="Unit Kerja" value="-" readonly>
                                    </div>
                                </div>

                                @else

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Grade</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="Grade" placeholder="Grade" value="{{ getInfoKaryawan($data['user']->karyawan->NPK) == null ? '-' : getInfoKaryawan($data['user']->karyawan->NPK)->grade }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Jabatan</label>
                                    <input type="hidden" name="IDMasterPosition" value="{{ $data['user']->karyawan->organization->PositionID }}">
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="Jabatan" placeholder="Jabatan" value="{{ getInfoKaryawan($data['user']->karyawan->NPK) == null ? '-' : getInfoKaryawan($data['user']->karyawan->NPK)->NAMA_POSISI }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Kode Unit Kerja</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="KodeUnitKerja" placeholder="Kode Unit Kerja" value="{{ getInfoKaryawan($data['user']->karyawan->NPK) == null ? '-' : getInfoKaryawan($data['user']->karyawan->NPK)->KodeUnitKerja }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Unit Kerja</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="DeskripsiUnitKerja" placeholder="Unit Kerja" value="{{ getInfoKaryawan($data['user']->karyawan->NPK) == null ? '-' : getInfoKaryawan($data['user']->karyawan->NPK)->UnitKerja }}" readonly>
                                    </div>
                                </div>

                                @endif
                                
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
                                

                                @if($data['user']->IDRole == 8) 
                                    @php
                                    $atasanlangsung = \DB::table("Ms_Direksi")->where("Npk", '4204634')->first(); 
                                    @endphp
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label text-left">NPK</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="NPKAtasanLangsung" id="npkatasanlangsung" placeholder="NPK" readonly value="{{ $atasanlangsung->Npk }}"> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label text-left">Nama</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="NamaAtasanLangsung" id="namaatasanlangsung" placeholder="Nama" readonly value="{{ $atasanlangsung->Nama }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label text-left">Jabatan</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="JabatanAtasanLangsung" id="jabatanatasanlangsung" placeholder="Jabatan" readonly value="{{ $atasanlangsung->Jabatan }}">
                                        </div>
                                    </div> 
                                @else

                                    @if($data["atasanLangsung"] != null && $data["atasanLangsung"]->Grade == "0D") 
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label text-left">NPK</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" name="NPKAtasanLangsung" id="npkatasanlangsung" placeholder="NPK" value="{{ (! empty(old('NPKAtasanLangsung'))) ? old('NPKAtasanLangsung') : ((! empty($data['atasanLangsung'])) ? $data['atasanLangsung']->Npk : null) }}">
                                                <span class="help-block">Ketik NPK untuk mencari data atasan.</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label text-left">Nama</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" name="NamaAtasanLangsung" id="namaatasanlangsung" placeholder="Nama" value="{{ (! empty(old('NamaAtasanLangsung'))) ? old('NamaAtasanLangsung') : ((! empty($data['atasanLangsung'])) ? $data['atasanLangsung']->Nama : null) }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label text-left">Jabatan</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" name="JabatanAtasanLangsung" id="jabatanatasanlangsung" placeholder="Jabatan" value="{{ (! empty(old('JabatanAtasanLangsung'))) ? old('JabatanAtasanLangsung') : ((! empty($data['atasanLangsung'])) ? $data['atasanLangsung']->Jabatan : null) }}" readonly>
                                            </div>
                                        </div>
                                    @else
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
                                    @endif
                                @endif 
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


                                @if($data['user']->IDRole == 8) 
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label text-left">NPK</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="NPKAtasanBerikutnya" id="npkatasanlangsung" placeholder="NPK" readonly value="{{ $atasanlangsung->Npk }}"> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label text-left">Nama</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="NamaAtasanBerikutnya" id="namaatasanlangsung" placeholder="Nama" readonly value="{{ $atasanlangsung->Nama }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label text-left">Jabatan</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="JabatanAtasanBerikutnya" id="jabatanatasanlangsung" placeholder="Jabatan" readonly value="{{ $atasanlangsung->Jabatan }}" >
                                        </div>
                                    </div> 
                                @else

                                    @if($data["atasanTakLangsung"] != null && $data["atasanTakLangsung"]->Grade == "0D") 
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label text-left">NPK</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" name="NPKAtasanBerikutnya" id="npkatasantaklangsung" placeholder="NPK" value="{{ (! empty(old('NPKAtasanBerikutnya'))) ? old('NPKAtasanBerikutnya') : ((! empty($data['atasanTakLangsung'])) ? $data['atasanTakLangsung']->Npk : null) }}">
                                                <span class="help-block">Ketik NPK untuk mencari data atasan.</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label text-left">Nama</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" name="NamaAtasanBerikutnya" id="namaatasantaklangsung" placeholder="Nama" value="{{ (! empty(old('NamaAtasanBerikutnya'))) ? old('NamaAtasanBerikutnya') : ((! empty($data['atasanTakLangsung'])) ? $data['atasanTakLangsung']->Nama : null) }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label text-left">Jabatan</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" name="JabatanAtasanBerikutnya" id="jabatanatasantaklangsung" placeholder="Jabatan" value="{{ (! empty(old('JabatanAtasanBerikutnya'))) ? old('JabatanAtasanBerikutnya') : ((! empty($data['atasanTakLangsung'])) ? $data['atasanTakLangsung']->Jabatan : null) }}" readonly>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label text-left">NPK check</label>
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
                                    @endif

                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-right save-container">
                        <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                        <a href="{{ route('backends.kpi.rencana.individu') }}" class="btn btn-default btn-orange">Batal</a>
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
                    onSelectItemEvent: function(value) {
                        var nama = $("#npk" + attribute).getSelectedItemData().NamaKaryawan;
                        var npk = $("#npk" + attribute).getSelectedItemData().NPK;

                        if(npk == '4204634'){
                            var jabatan = 'Direktur Utama';
                        }else if(npk == '4204635'){
                            var jabatan = 'Direktur Produksi';
                        }else if(npk == '4204636'){
                            var jabatan = 'Direktur Komersil';
                        } else{
                            var jabatan = $("#npk" + attribute).getSelectedItemData().organization.position.PositionTitle;
                        }

                        
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
