@inject('targetparser', 'Pkt\Domain\Rencana\Services\TargetParserService')

@php
    $abbrev = auth()->user()->abbreviation()!=null ? auth()->user()->abbreviation()->isUnitKerja():false;
@endphp

@extends('layouts.app')
@extends('backends.kpi.rencana.kamus.kamus')
@section('submenu')
    @include('layouts.submenu.rencana')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 margin-top-15">
                <ul class="kpi-menu">
                    <li>
                        <a href="{{ route('backends.kpi.rencana.individu.editdetail', ['id' => $data['header']->ID]) }}">Rencana KPI</a>
                    </li>
                    @if($abbrev)
                    <li {!! (strpos(request()->path(), 'unitkerja') !== false) ? 'class="active"' : null !!}>
                        <a href="{{ route('backends.kpi.rencana.individu.unitkerja.index', ['id' => $data['header']->ID]) }}">KPI Unit Kerja</a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('backends.kpi.rencana.individu.pennurunan', ['id' => $data['header']->ID]) }}">Penurunan KPI</a>
                    </li>
                    <li>
                        <a href="{{ route('backends.kpi.rencana.individu.indexdocument', ['id' => $data['header']->ID]) }}">Dokumen</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <form id="detailrencanaform" class="form-horizontal" method="post" action="{{ $data['route'] }}">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="put">
                    <input type="hidden" name="KodeRegistrasiKamus" class="KodeRegistrasiKamus" value="{{ $data['detail']->KodeRegistrasiKamus}}">
                    @if($data["edit_realisasi"])
                        <input type="hidden" name="realisasi" value="realisasi">
                    @endif


                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body"> 
                            <div class="col-sm-12">
                                <div class="panel-title-box no-border">Edit Item KPI</div>
                                @include('vendor.flash.message') 
                            </div>
                            <div class="col-sm-11 col-sm-offset-1">
                                @if(!$data["edit_realisasi"])
                                <div id="message-kamus" class="alert alert-warning alert-dismissable alert-important" style="visibility: hidden;">
                                    <ul>
                                        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
                                        <li style="list-style:none;">Menggunakan kamus akan menunda input manual<b> buat ulang </b>untuk memulihkan</li>
                                    </ul>
                                </div>
                                @endif
                                <input type="hidden" id="IDJenisPeriode" name="IDJenisPeriode" value="{{ $data['periode']->IDJenisPeriode }}">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Deskripsi KRA</label>
                                    <div class="col-sm-6">
                                        <textarea name="DeskripsiKRA" class="form-control" rows="3" {{ ($data['detail']->isCascaded()) ? 'readonly' : '' }}>{{ $data['detail']->DeskripsiKRA }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group form-inline">
                                    <label class="col-sm-2 control-label">Deskripsi KPI</label>
                                    <div class="col-sm-6">
                                        <textarea name="DeskripsiKPI" class="form-control" rows="3" id="deskripsiKPI" style="min-width: 100%;">{{ $data['detail']->DeskripsiKPI }}</textarea>
                                    </div>
                                    <div class="col-sm-2">
                                        @if(!$data['edit_realisasi'])
                                            <button type="button" class="btn btn-default btn-yellow" onclick="modalKamus()">Gunakan Kamus</button> 
                                        @endif
                                        
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Kelompok</label>
                                    <div class="col-sm-5"> 
                                       <select {{ $data['edit_realisasi'] ? 'disabled' : '' }}  {{ $data['detail']->IDKodeAspekKPI == 2 || $data["detail"]->IDKRAKPIRencanaDetil != null ? 'disabled' : '' }} class="form-control" name="IDKodeAspekKPI" id="IDKodeAspekKPI" onchange="kodeAspek()">
                                            <option value="">-- Pilih Aspek KPI --</option>
                                            <option value="1" {{ $data["edit_realisasi"] ? 'disabled' : '' }} {{ $data['detail']->IDKodeAspekKPI == 1 ? 'selected' : '' }} >Strategis</option>
                                            <option value="2" {{ $data["edit_realisasi"] ? 'disabled' : '' }} {{ $data['detail']->IDKodeAspekKPI == 2 ? 'selected' : '' }}>Rutin Struktural</option>
                                            <option value="3" {{ $data["edit_realisasi"] ? 'disabled' : '' }} {{ $data['detail']->IDKodeAspekKPI == 3 ? 'selected' : '' }}>Rutin Operasional</option>
                                            <option value="4" {{ $data['detail']->IDKodeAspekKPI == 4 ? 'selected' : '' }}>Task Force</option>
                                        </select> 

                                         <!-- <select  {{ $data['detail']->IDKodeAspekKPI == 2 ? 'disabled' : '' }} class="form-control" name="IDKodeAspekKPI" id="IDKodeAspekKPI" onchange="kodeAspek()">
                                            <option value="">-- Pilih Aspek KPI --</option>
                                            <option value="1" {{ $data["edit_realisasi"] ? 'disabled' : '' }} {{ $data['detail']->IDKodeAspekKPI == 1 ? 'selected' : '' }} >Strategis</option>
                                            <option value="2" {{ $data["edit_realisasi"] ? 'disabled' : '' }} {{ $data['detail']->IDKodeAspekKPI == 2 ? 'selected' : '' }}>Rutin Struktural</option>
                                            <option value="3" {{ $data["edit_realisasi"] ? 'disabled' : '' }} {{ $data['detail']->IDKodeAspekKPI == 3 ? 'selected' : '' }}>Rutin Operasional</option>
                                            <option value="4" {{ $data['detail']->IDKodeAspekKPI == 4 ? 'selected' : '' }}>Task Force</option>
                                        </select> -->
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Sifat</label>
                                   <div class="col-sm-5">
                                        {!! Form::select('IDJenisAppraisal', ['' => '-- Pilih Sifat --'] + $data['jenisAppraisal'], $data['detail']->IDJenisAppraisal, ['class' => 'form-control changeme','id'=>'IDJenisAppraisal', 'disabled' => $data["detail"]->IDKRAKPIRencanaDetil != null ? true : false ]) !!}
                                    </div> 

                                     <!-- <div class="col-sm-5">
                                        {!! Form::select('IDJenisAppraisal', ['' => '-- Pilih Sifat --'] + $data['jenisAppraisal'], $data['detail']->IDJenisAppraisal, ['class' => 'form-control changeme','id'=>'IDJenisAppraisal']) !!}
                                    </div> -->
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Jenis KPI</label>
                                    <div class="col-sm-5">
                                        {!! Form::select('IDPersentaseRealisasi', ['' => '-- Jenis KPI --'] + $data['persentaseRealisasi'], $data['detail']->IDPersentaseRealisasi, ['class' => 'form-control changeme','id'=>'IDPersentaseRealisasi', 'disabled' => $data["detail"]->IDKRAKPIRencanaDetil != null ? true : false ]) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Satuan</label>
                                    <div class="col-sm-5">
                                        {!! Form::select('IDSatuan', ['' => '-- Pilih Satuan --'] + $data['satuan'], $data['detail']->IDSatuan, ['class' => 'form-control changeme','id'=>'IDSatuan', 'disabled' => $data["detail"]->IDKRAKPIRencanaDetil != null ? true : false]) !!}
                                    </div>
                                </div>
                                @for($x = 1; $x <= $data['target']; $x++) 
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Target {{$data['periodeTarget']}} - {{$x}}</label>
                                        <div class="col-sm-5">
                                            <input type="number" step="any" min="0" lang="en" pattern="-?[0-9]+[\,.]*[0-9]+" name="Target{{$targetparser->targetCount($data['target'])[$x-1]}}"  class="form-control double required" {!! $data["detail"]->IDKRAKPIRencanaDetil != null ? 'readonly' : ''  !!} placeholder="Target {{$x}}" value="{{ floatval($data['detail']->{'Target'.$targetparser->targetCount($data['target'])[$x-1]}) }}">
                                        </div>
                                    </div>
                                @endfor
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Sebagai KPI Bawahan</label>
                                    <div class="col-sm-4">
                                        <ul class="container-check">
                                            <li>
                                                <input type="radio" id="s-option" name="IsKRABawahan" value="0" {{$data['detail']->IsKRABawahan == false ? 'checked' : ''}} {{ $data['detail']->isTaskForce() ? 'disabled' : '' }}>
                                                <label for="s-option">Tidak</label>
                                                <div class="check"><div class="inside"></div></div>
                                            </li>
                                            <li>
                                                <input type="radio" id="f-option" name="IsKRABawahan" value="1" {{$data['detail']->IsKRABawahan == true ? 'checked' : ''}} {{ $data['detail']->isTaskForce() ? 'disabled' : '' }}>
                                                <label for="f-option">Ya</label>
                                                <div class="check"></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div>
                                     <div class="form-group">
                                        <label class="col-sm-2 control-label">Bobot </label> 
                                        <div class="col-sm-2">

                                            <!-- {{ $data["detail"]->IDKRAKPIRencanaDetil != null ? 'disabled' : '' }}  -->

                                            <div class="input-group">
                                                 <input type="text" id="Bobot" name="Bobot" class="form-control double" placeholder="Bobot" value="{{ $data['detail']->Bobot }}" {{ ($data['detail']->isMandatory() || $data['detail']->isTaskForce()) ? 'readonly' : '' }}>
                                                <span class="input-group-addon"> % </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >Keterangan</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="Keterangan" class="form-control" placeholder="Keterangan (opsional)" id="Keterangan" value="{{$data['detail']->Keterangan}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-7 save-container">
                                        <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                                        @if(array_key_exists('source', $data))
                                            <a href="{{ route('backends.kpi.rencana.individu.unitkerja.index', ['id' => $data['header']->ID]) }}" class="btn btn-default btn-orange">Batal</a>
                                        @else
                                            <a href="{{ route('backends.kpi.rencana.individu.editdetail', ['id' => $data['header']->ID]) }}" class="btn btn-default btn-orange">Batal</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('customjs')
    <script>
        document.getElementById('detailrencanaform').onsubmit=function () {
            var kamus =  $('input[type=\'radio\'][name=\'KodeRegistrasiKamus\']:checked');
            if(kamus.val()!==null)
            $('.KodeRegistrasiKamus').val(kamus.val());
            $('#IDKodeAspekKPI').removeAttr('disabled');
            $('#IDJenisAppraisal').removeAttr('disabled');
            $('#IDPersentaseRealisasi').removeAttr('disabled');
            $('#IDSatuan').removeAttr('disabled');
            $('#Bobot').removeAttr('disabled');
            $('#s-option').removeAttr('disabled');
            $('#f-option').removeAttr('disabled');
        };
        $(function () {
            $('.double').keypress(
                function (evt) {
                    if (evt.which != 8 && evt.which != 0 && evt.which != 46 && evt.which < 48 || evt.which > 57) {
                        evt.preventDefault();
                    }
                });
        });

        function kodeAspek() {
            var kode = $('#IDKodeAspekKPI').val();

            if(kode == 2) {
                $('#Bobot').val(5).prop('readonly', true).prop('disabled', true);
                if('{{ $data['posisi'] }}' != 'DF') {
                    $('#s-option').prop('disabled', false);
                    $('#f-option').prop('disabled', false);
                }
            }
            else if(kode == 4) {
                $('#Bobot').val(2).prop('readonly', true).prop('disabled', false);
                if('{{ $data['posisi'] }}' != 'DF') {
                    $('#s-option').prop('disabled', true);
                    $('#f-option').prop('disabled', true);
                }
            }
            else {
                $('#Bobot').val(null).prop('readonly', false).prop('disabled', false);
                if('{{ $data['posisi'] }}' != 'DF') {
                    $('#s-option').prop('disabled', false);
                    $('#f-option').prop('disabled', false);
                }
            }
        }
    </script>
@endsection
