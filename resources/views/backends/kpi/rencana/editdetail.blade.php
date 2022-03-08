@inject('targetparser', 'Pkt\Domain\Rencana\Services\TargetParserService')
@php
    $abbrev = auth()->user()->abbreviation()!=null ? auth()->user()->abbreviation()->isUnitKerja():false;
@endphp

@extends('layouts.app')
@include('backends.kpi.rencana.kamus.kamus')
@section('submenu')
    @include('layouts.submenu.rencana')
@endsection
@section('content')
    <div id="container">   
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 margin-top-15">
                <ul class="kpi-menu">
                    <li class="active">
                        <a href="{{ route('backends.kpi.rencana.individu.editdetail', ['id' => $data['header']->ID]) }}">Rencana KPI</a>
                    </li>
                    @if($abbrev)
                    <li>
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
                <div class="col-md-12 col-sm-12 margin-top-30">
                    <form id="detailrencanaform" class="form-horizontal" method="post" action="{{ route('backends.kpi.rencana.individu.storedetail', ['id' => $data['header']->ID]) }}">
                    {!! csrf_field() !!}
                        <input type="hidden" name="KodeRegistrasiKamus" class="KodeRegistrasiKamus">
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="panel-title-box no-border">Form Item KPI<span class="label label-info pull-right">{{ strtoupper($data['header']->statusdokumen->StatusDokumen) }}</span></div>
                                @include('vendor.flash.message')
                            </div>
                            <div class="col-sm-12">
                                <div class="panel-group panel-faq" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="heading" style="background-color: #337ab7;">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse" aria-expanded="true" aria-controls="collapse">
                                                <span class="fa-stack" style="float: right;margin-top: -0.5%;">
                                                    <i class="fa fa-circle fa-inverse fa-stack-2x"></i>
                                                    <i class="more-less fa fa-stack-2x fa-angle-right"></i>
                                                </span>
                                                <h4 class="panel-title" style="color: white;">
                                                   <b>Data Karyawan</b>
                                                </h4>
                                            </a>
                                        </div>
                                        <div id="collapse" class="panel-collapse collapse row margin-bottom-15" role="tabpanel" aria-labelledby="heading">
                                            <div class="col-sm-12">
                                                <div class="panel panel-primary margin-top-15">
                                                    <div class="panel-body">
                                                        
                                                        @if(Auth::user()->IDRole == 8)
                                                            @php

                                                                $direksi = \DB::table("Ms_Direksi")->where("Npk", Auth::user()->NPK)->first();
                                                            @endphp

                                                            <ul class="list-group">
                                                            <li class="list-group-item"><b>Tahun</b><span class="pull-right">{{ $data['header']->Tahun }}</span></li>
                                                            <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['header']->NPK }}</span></li>
                                                            <li class="list-group-item"><b>Nama Karyawan</b><span class="pull-right">{{ $data['karyawan']->NamaKaryawan }}</span> </li>
                                                            <li class="list-group-item"><b>Grade</b><span class="pull-right">{{ $data['header']->Grade }}</span> </li>
                                                            <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ $direksi->Jabatan }}</span> </li>
                                                            <li class="list-group-item"><b>Kode Unit Kerja</b><span class="pull-right">{{ $direksi->KodeUnitKerja }}</span></li> 
                                                             
                                                        @else
                                                            <ul class="list-group">
                                                            <li class="list-group-item"><b>Tahun</b><span class="pull-right">{{ $data['header']->Tahun }}</span></li>
                                                            <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['header']->NPK }}</span></li>
                                                            <li class="list-group-item"><b>Nama Karyawan</b><span class="pull-right">{{ $data['karyawan']->NamaKaryawan }}</span> </li>
                                                            <li class="list-group-item"><b>Grade</b><span class="pull-right">{{ $data['header']->Grade }}</span> </li>
                                                            <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ $data['karyawan']->organization->position->PositionTitle }}</span> </li>
                                                            <li class="list-group-item"><b>Kode Unit Kerja</b><span class="pull-right">{{ $data['karyawan']->organization->position->KodeUnitKerja }}</span></li>
                                                            <li class="list-group-item"><b>Unit Kerja</b><span class="pull-right">{{ $data['karyawan']->organization->position->unitkerja->Deskripsi }}</span> </li>
                                                        </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="panel panel-primary margin-top-15">
                                                    <div class="panel-heading" style="background-color: #337ab7">Atasan Langsung</div>
                                                    <div class="panel-body">
                                                        <ul class="list-group">
                                                            <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['atasanLangsung'] != null ? $data['atasanLangsung']->NPK : '-' }}</span></li>
                                                            <li class="list-group-item"><b>Nama</b><span class="pull-right">{{ $data['atasanLangsung'] != null ? $data['atasanLangsung']->NamaKaryawan : '-' }}</span></li>
                                                            <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ $data['header']->JabatanAtasanLangsung }}</span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="panel panel-primary margin-top-15">
                                                    <div class="panel-heading" style="background-color: #337ab7">Atasan Tak Langsung</div>
                                                    <div class="panel-body">
                                                        <ul class="list-group">
                                                            <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['atasanTakLangsung'] != null ? $data['atasanTakLangsung']->NPK : '-' }}</span></li>
                                                            <li class="list-group-item"><b>Nama</b><span class="pull-right">{{ $data['atasanTakLangsung'] != null ? $data['atasanTakLangsung']->NamaKaryawan : '-' }}</span></li>
                                                            <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ $data['header']->JabatanAtasanBerikutnya }}</span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($data['header']->IDStatusDokumen == 1)
                            <div class="col-sm-11 col-sm-offset-1">
                                <div id="message-kamus" class="alert alert-warning alert-dismissable alert-important" style="visibility: hidden;">
                                    <ul>
                                        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
                                        <li style="list-style:none;">Menggunakan kamus akan menunda input manual<a onclick="location.reload();" style="cursor: hand;"> refresh laman </a>untuk memulihkan</li>
                                    </ul>
                                </div>
                                <input type="hidden" id="IDJenisPeriode" name="IDJenisPeriode" value="{{ $data['periode']->pluck('IDJenisPeriode')->first() }}">
                                <div class="form-group">
                                    <label class="col-xs-2 control-label">Deskripsi KRA</label>
                                    <div class="col-xs-6">
                                        <textarea name="DeskripsiKRA" class="form-control" rows="3">{{ old('DeskripsiKRA') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group form-inline">
                                    <label class="col-xs-2 control-label">Deskripsi KPI</label>
                                    <div class="col-xs-6">
                                        <textarea name="DeskripsiKPI" class="form-control" rows="3" id="deskripsiKPI" style="min-width: 100%;">{{ old('DeskripsiKPI') }}</textarea>
                                    </div>
                                    <div class="col-xs-2">
                                        <button type="button" class="btn btn-default btn-yellow" onclick="modalKamus()">Gunakan Kamus</button>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Kelompok</label>
                                    <div class="col-sm-5">
                                       <!--  {!! Form::select('IDKodeAspekKPI',['' => '-- Pilih Aspek KPI --'] + $data['aspekKpi'], old('IDKodeAspekKPI'), ['class' => 'form-control changeme', 'id'=>'IDKodeAspekKPI', 'onchange' => 'kodeAspek()']) !!} -->

                                       <select name="IDKodeAspekKPI" id="IDKodeAspekKPI" class="form-control changeme" onchange="kodeAspek()">
                                            <option value="">-- Pilih Aspek KPI --</option>
                                            @foreach ($data['aspekKpi'] as $key => $item) 
                                                @if(in_array(Auth::user()->karyawan->organization->Grade, ['1A ','1B ']))
                                                <option {{ old('IDKodeAspekKPI') == $key ? "selected" : "" }} value="{{ $key }}">{{ $item }}</option>
                                                @else
                                                <option {{ old('IDKodeAspekKPI') == $key ? "selected" : "" }} value="{{ $key }}" {{ (strtolower($item) == 'rutin struktural' ? 'disabled' : '') }}>{{ $item }}</option>
                                                @endif 
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Sifat</label>
                                    <div class="col-sm-5">
                                        {!! Form::select('IDJenisAppraisal', ['' => '-- Pilih Sifat --'] + $data['jenisAppraisal'], old('IDJenisAppraisal'), ['class' => 'form-control changeme','id'=>'IDJenisAppraisal']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Jenis KPI</label>
                                    <div class="col-sm-5">
                                        {!! Form::select('IDPersentaseRealisasi', ['' => '-- Jenis KPI --'] + $data['persentaseRealisasi'], old('IDPersentaseRealisasi'), ['class' => 'form-control changeme','id'=>'IDPersentaseRealisasi']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Satuan</label>
                                    <div class="col-sm-5">
                                        {!! Form::select('IDSatuan', ['' => '-- Pilih Satuan --'] + $data['satuan'], old('IDSatuan'), ['class' => 'form-control changeme','id'=>'IDSatuan']) !!}
                                    </div>
                                </div>
                                @for($x = 1; $x <= $data['target']; $x++)
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Target {{$data['periodeTarget']}} - {{$x}}</label>
                                        <div class="col-sm-5">
                                            <input type="number" step="any" min="0" lang="en" pattern="-?[0-9]+[\,.]*[0-9]+" name="Target{{ $targetparser->targetCount($data['target'])[$x-1] }}"  class="form-control double" placeholder="Target {{$x}}" value="{{ old('Target'.$targetparser->targetCount($data['target'])[$x-1])}}" required>
                                        </div>
                                    </div>
                                @endfor
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Sebagai KPI Bawahan</label>
                                    <div class="col-sm-4">
                                        <ul class="container-check">
                                            <li>
                                                <input type="radio" id="s-option" name="IsKRABawahan" value="0" {{old('IsKRABawahan')==false?'checked':''}} {{ $data['posisi']=='DF'?'disabled':'' }}>
                                                <label for="s-option">Tidak</label>
                                                <div class="check"><div class="inside"></div></div>
                                            </li>
                                            <li>
                                                <input type="radio" id="f-option" name="IsKRABawahan" value="1" {{old('IsKRABawahan')==true?'checked':''}} {{ $data['posisi']=='DF'?'disabled':'' }}>
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
                                            <div class="input-group">
                                                <input type="text" name="Bobot" class="form-control double" placeholder="Bobot" id="Bobot" value="{{ old('Bobot') }}" required>
                                                <span class="input-group-addon"> % </span>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Keterangan</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="Keterangan" class="form-control" placeholder="Keterangan (opsional)" id="Keterangan" value="{{old('Keterangan')}}">
                                        </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"></label>
                                    <div class="col-sm-7">
                                        <button type="submit" class="btn btn-default btn-blue">Tambah</button>
                                        <a href="{{ route('backends.kpi.rencana.individu') }}" class="btn btn-default btn-orange">Batal</a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </form>
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="panel-title-box no-border no-margin-bottom">Item KPI</div>
                            </div>
                            @if($data['header']->IDStatusDokumen == 1)
                                <div class="col-sm-6">
                                    <div class="custom-button-container">
                                        <a href="#">
                                            <button class="btn btn-link" id="splitItemKPI">
                                                <i class="fa fa-cubes text-blue"></i> Split
                                            </button>
                                        </a>

                                        <a href="#">
                                            <button class="btn btn-link" id="unsplitItemKPI">
                                                <i class="fa fa-cube text-blue"></i> Unsplit
                                            </button>
                                        </a>

                                        <a href="#">
                                            <button class="btn btn-link" id="updateItemKPI">
                                                <img src="{{ asset('assets/img/ic_update.png') }}"> Edit / Update
                                            </button>
                                        </a>
                                        <a href="#">
                                            <button class="btn btn-link" id="deleteItemKPI">
                                                <img src="{{ asset('assets/img/ic_add.png') }}" class="rotate-30"> Hapus
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <div class="margin-min-15" style="overflow-x: scroll; width: calc(100% + 30px);">
                                <table id="detailkpi-table" class="table" width="100%">
                                    <thead>
                                    <tr>
                                        <th style="min-width: 10px;">No.</th>
                                        @if($data['header']->IDStatusDokumen == 1)
                                            <th class="text-center" style="min-width:10px;"><input type="checkbox" id="checkAll"></th>
                                        @endif
                                        <th>Kelompok</th>
                                        <th>KRA</th>
                                        <th>KPI</th>
                                        <th>Bobot</th>
                                        <th>Satuan</th>
                                        @for($i=1; $i<=$data['target']; $i++)
                                            <th>Target {{$data['periodeTarget']}} - {{ $i }}</th>
                                        @endfor
                                        <th>Keterangan</th>
                                        <th>Sifat</th>
                                        <th>Jenis KPI</th>
                                        <th>Sbg KPI Bawahan</th>
                                        <th>KPI dari atasan</th>
                                        <th>Created on</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $totalBobot = 0;
                                    @endphp
                                    @forelse($data['alldetail'] as $detail)
                                        <tr class="{{ ($detail->isAsKRABawahan()) ? rowLabelCascadingIsComplete($detail->penurunan) : 'success' }}">
                                            <td></td>
                                            @if($data['header']->IDStatusDokumen == 1)
                                                <td class="text-center"><input type="checkbox" name="check[]" is_split="{{ $detail->IDSplitParent == null ? 0 : 1 }}" value="{{ $detail->ID }}"></td>
                                            @endif
                                            <td>{{ $detail->aspekkpi->AspekKPI }}</td>
                                            <td>{{ $detail->DeskripsiKRA }}</td>
                                            <td>{{ $detail->DeskripsiKPI }}</td>
                                            <td>{{ $detail->Bobot }}%</td>
                                            @php
                                                $totalBobot += $detail->Bobot;
                                            @endphp
                                            <td>{{ $detail->satuan->Satuan }}</td>
                                            @for($i=1; $i<=$data['target']; $i++)
                                                <td>{{ (! is_null($detail->{'Target'.$targetparser->targetCount($data['target'])[$i-1]})) ? numberDisplay($detail->{'Target'.$targetparser->targetCount($data['target'])[$i-1]}) : '-'  }}</td>
                                            @endfor
                                            <td>{{ $detail->kpiatasan->Keterangan or $detail->Keterangan }}</td>
                                            <td>{{ $detail->jenisappraisal->JenisAppraisal }}</td>
                                            <td>{{ $detail->persentaserealisasi->PersentaseRealisasi }}</td>
                                            <td class="text-center">{!! $detail->IsKRABawahan ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>' !!}</td>
                                            <td> 
                                                {!! $detail->IDKRAKPIRencanaDetil != null ? 'Iya' : 'Tidak' !!}
                                            </td>
                                            <td>
                                                {{ $detail->CreatedOn }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{$data['header']->IDStatusDokumen == 1? 10 + $data['target']: 8 + $data['target']}}" class="text-center">Tidak ada data detail rencana KPI</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="5">Total Bobot</th>
                                        <th>{{ $totalBobot }}%</th>
                                    </tr>
                                    <tr>
                                        <th colspan="5">Total Item KPI</th>
                                        <th>{{ $data['alldetail']->count() }}</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center">
                                <button class="btn btn-default btn-blue" id="register" data-idrencana="{{ $data['header']->ID }}"><i class="fa fa-check "></i> Register Rencana KPI</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade modal-notification" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ asset('assets/img/ic_warning.png') }}" class="img-warning-modal">
                        <h4 class="modal-title" id="myModalLabel">Detail Data KPI</h4>
                       Already updated!
                    </div>
                    <div class="text-right save-container">
                        <button type="button" class="btn btn-default btn-yellow">Ya</button>
                        <button type="button" class="btn btn-default btn-no" data-dismiss="modal">Tidak</button>
                    </div>
                </div>
            </div>
        </div>
    <div class="modal fade modal-notification" id="actionConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close noCallButton" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('assets/img/ic_warning.png') }}" class="img-warning-modal">
                    <h4 class="modal-title" id="actionModalTitle"></h4>
                    <p id="actionModalContent"></p>
                    <form id="modalform" class="form-horizontal">
                        {!! csrf_field() !!}
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-yellow" id="callButton">Ya</button>
                    <button type="button" class="btn btn-default btn-no noCallButton" data-dismiss="modal">Tidak</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-split" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Split item KPI</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="form-split" method="post">
                {{ csrf_field() }}
                <div class="modal-body">

                    <input type="hidden" name="ID" id="ID">
                    <input type="hidden" id="jenisappraisal" name="IDJenisAppraisal">
                    <input type="hidden" id="target" name="maxTerget">
                    <input type="hidden" id="bobot" name="maxBobot">

                    <table class="table table-bordered" id="form-table-split"> 
                        <tbody>
                            <tr>
                                <td>No</td>
                                <td width="auto">Deskripsi KRA</td>
                                <td>Deskripsi KPI</td>
                                <td>Sebagai KPI Bawahan</td>
                                <td width="20%">Target THN-1 <div style="float: right;"><input type="checkbox" checked="checked" id="auto-target" name=""> Auto</div></td>
                                <td width="15%">Bobot</td>
                                <td width="1px">Aksi</td>
                            </tr>
                        </tbody>
                        <tbody id="layout-parent">
                            <tr>
                                <td class="numbering">1.</td>
                                <td class="deskripsi-kra"></td>
                                <td>
                                    <textarea class="form-control deskripsi-kpi" name="deskripsi_kpi[]"></textarea>
                                </td>
                                <td><input type="checkbox" value="1" name="is_turunan[]"></td>
                                <td>
                                    <input type="text" class="form-control target" name="target[]">
                                </td>
                                <td>
                                    <div class="input-group">
                                    <input type="number" step="any" min="0" pattern="-?[0-9]+[\,.]*[0-9]+" class="form-control double bobot" name="bobot[]" placeholder="Persentase KRA" value="100" lang="en">
                                        <span class="input-group-addon" id="basic-addon2">%</span>
                                    </div>
                                </td>
                                <td>
                                    <button id="add-row" class="btn btn-success"><i class="fa fa-plus"></i></button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody id="layout-child">
                            <tr></tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" align="right">
                                    Total
                                </td>
                                <td id="layout-total-target"><span id="target-min">0.00</span> / <span id="target-max"></span></td>
                                <td><span id="bobot-min"></span> / <span id="bobot-max"></span></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer" style="margin-top: -50px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Split</button>
                </div> 
            </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-unsplit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Unsplit item KPI</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div> 
            <div class="modal-body">
 
                <table class="table table-sm table-bordered"> 
                    <tbody>
                        <tr>
                            <td>No</td>
                            <td width="auto">Deskripsi KRA</td>
                            <td>Deskripsi KPI</td>
                            <td>Sebagai KPI Bawahan</td>
                            <td width="20%">Target THN-1</td>
                            <td width="15%">Bobot</td> 
                        </tr>
                    </tbody>
                    <tbody id="unsplit-item">
                        
                    </tbody>                      
                </table>

                <div class="text-center">
                    <i class="fa fa-arrow-down fa-3x"></i>
                </div>

                <table class="table table-sm table-bordered margin-top-15"> 
                    <tbody>
                        <tr>
                            <td>No</td>
                            <td width="auto">Deskripsi KRA</td>
                            <td>Deskripsi KPI</td>
                            <td>Sebagai KPI Bawahan</td>
                            <td width="20%">Target THN-1</td>
                            <td width="15%">Bobot</td> 
                        </tr>
                    </tbody>
                    <tbody id="normal-unsplit">
                        
                    </tbody> 
                     
                </table>
            </div>
            <div class="modal-footer" style="margin-top: -50px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="unsplit">Unsplit</button>
            </div> 
        </div>
      </div>
    </div>

    </div>
@endsection

@section('customjs')
    <link rel="stylesheet" type="text/css" href="https://sehat.pupukkaltim.com/css/waitme.min.css">
    <script type="text/javascript" src="https://sehat.pupukkaltim.com/js/waitme.min.js"></script>

    <script>
        document.getElementById('detailrencanaform').onsubmit=function () {
            $('.KodeRegistrasiKamus').val($('input[type=\'radio\'][name=\'KodeRegistrasiKamus\']:checked').val());
            $('#IDKodeAspekKPI').removeAttr('disabled');
            $('#IDJenisAppraisal').removeAttr('disabled');
            $('#IDPersentaseRealisasi').removeAttr('disabled');
            $('#IDSatuan').removeAttr('disabled');
            $('#Bobot').removeAttr('disabled');
            $('#s-option').removeAttr('disabled');
            $('#f-option').removeAttr('disabled');
        }
    </script>
    <script>
        var autoCompleteTarget = true; 

        $(function () {
            $('.double').keypress(
                function (evt) {
                if (evt.which != 8 && evt.which != 0 && evt.which != 46 && evt.which < 48 || evt.which > 57) {
                    evt.preventDefault();
                }
            });
        });

        function toggleIcon(e) {
            $(e.target)
                .prev('.panel-heading')
                .find(".more-less")
                .toggleClass('fa-angle-right fa-angle-down');
        }
        $('.panel-group').on('hidden.bs.collapse', toggleIcon);
        $('.panel-group').on('shown.bs.collapse', toggleIcon);

        @if($data['alldetail']->count() > 0)
        $('#detailkpi-table').DataTable({
            columnDefs: [{targets: [0,1], orderable: false}],
            pageLength: '{{ $data['alldetail']->count() }}',
            order: [],
            sDom: 'f',
            fnRowCallback: function (nRow, aData, iDisplayIndex) {
                var index = iDisplayIndex +1;
                $('td:eq(0)',nRow).html(index);
                return nRow;
            }
        });
        @endif

        $('#deleteItemKPI').click(function(event) {
            event.preventDefault();
            var checked = $(':checkbox:checked');
            var form = $('#modalform');
            var action = "{{ route('backends.kpi.rencana.individu.deleteItem') }}";
            var modalTitle = 'Hapus Item Rencana KPI Individu';
            var modalContent = 'Apakah anda yakin untuk menghapus item rencana KPI Anda?';
            form.append('<input type="hidden" name="_method" value="delete" class="appendedInput">');
            callAction(form, checked, action, modalTitle, modalContent);
        });

    $('.noCallButton').click(function () {
        $('#modalform').attr('action', '');
        $('.appendedInput').remove();
    });

    $('#register').click(function() {
        var form = $('#modalform');
        var action = "{{ route('backends.kpi.rencana.individu.register') }}";
        var modalTitle = 'Register Rencana KPI Individu';
        var modalContent = 'Apakah anda yakin untuk melakukan REGISTER Rencana KPI Individu Anda?';
        form.attr('action', action);
        form.append('<input type="hidden" name="id[]" value="'+ $(this).data('idrencana') + '" class="appendedInput">');
        $('#actionModalTitle').html(modalTitle);
        $('#actionModalContent').html(modalContent);
        $('#actionConfirmModal').modal('show');
        $('#callButton').click(function(event) {
            event.preventDefault();
            $(this).attr('disabled','disabled');
            $.ajax({
                url: action,
                type: 'post',
                data: form.serialize().replace(/%5B%5D/g, '[]'),
                success: function(result) {
                    if(result.status) {
                        window.location = "{{ route('backends.kpi.rencana.individu') }}";
                    } else {
                        alert(result.errors);
                        document.location.reload(true);
                    }
                },
                error: function(xhr) {
                    alert('Error : ' + xhr.statusText);
                    document.location.reload(true);
                }
            });
        });
    });

    // call action
    function callAction(form, checked, action, title, content) {
        if(checked.length === 0) {
            alert('Silakan pilih KPI terlebih dahulu.');
        } else {
            form.attr('action', action);
            checked.each(function(i) {
                if($(this).val() !== 'on') {
                    form.append('<input type="hidden" name="id[]" value="'+ $(this).val() + '" class="appendedInput">')
                }
            });
            $('#actionModalTitle').html(title);
            $('#actionModalContent').html(content);
            $('#actionConfirmModal').modal('show');
            $('#callButton').click(function(event) {
                event.preventDefault();
                $.ajax({
                    url: action,
                    type: 'post',
                    data: form.serialize().replace(/%5B%5D/g, '[]'),
                    success: function(result) {
                        if(result.status) {
                            document.location.reload(true);
                        } else {
                            alert(result.errors);
                            document.location.reload(true);
                        }
                    },
                    error: function(xhr) {
                        alert('Error : ' + xhr.statusText);
                        document.location.reload(true);
                    }
                });
            });
        }
    }

    $("#splitItemKPI").click(function(event){
        event.preventDefault();
        var checked = $('#detailkpi-table :checkbox:checked');
        if(checked.length == 0) {
            alert('Silakan pilih KPI terlebih dahulu.');
        }
        else if(checked.length !== 1)
        {
            alert('Pilih salah satu item KPI saja yang ingin di update.');
        }
        else if(checked.attr("is_split") == 1){
            alert('gak boleh di split');
        }
        else {
            var id = checked.val();
            var url = "{{ route('backends.kpi.rencana.individu.splititem',':id') }}"; 
            resetTable();
            
            $.ajax({
                url : url.replace(':id',id),
                type : 'GET',
                datatype : 'json',
                beforeSend: function(){
                    startLoader('.modal-content');
                },
                success : function(resp){
                    stopLoader('.modal-content');
                    if(resp.detail.IDKRAKPIRencanaDetil == null){
                        alert('Hanya KPI dari atasan saja yang dapat di split')
                        return false;
                    }else{
                        $("#ID").val(resp.detail.ID);
                        $("#jenisappraisal").val(resp.detail.IDJenisAppraisal);
                        $(".deskripsi-kra").html(resp.detail.DeskripsiKRA == null ? '-' : resp.detail.DeskripsiKRA);
                        $(".deskripsi-kpi").val(resp.detail.DeskripsiKPI);
                        $(".bobot:first").val(resp.detail.Bobot);
                        $(".target:first").val(resp.detail.Target12);
                        $("#target-max").html(resp.detail.Target12);
                        $("#target-min").html(resp.detail.Target12);
                        $("#bobot-max").html(resp.detail.Bobot + '%');
                        $("#bobot-min").html(resp.detail.Bobot + '%');
                        $("#target").val(resp.detail.Target12);
                        $("#bobot").val(resp.detail.Bobot);

                        if(resp.detail.IDJenisAppraisal == 2){
                            $(".target").prop("readonly", false);
                        }else{
                            $(".target").prop("readonly", true);
                        }
                        $("#modal-split").modal("show");
                    }     
                }
            });
        }
    });

    function convertNumeric($number){
        var string = $number.split('.').join("");
        return parseFloat(string.split(',').join("."));
    }

    function formatNumber(n, p, ts, dp) {
        var t = [];
        // Get arguments, set defaults
        if (typeof p  == 'undefined') p  = 2;
        if (typeof ts == 'undefined') ts = ',';
        if (typeof dp == 'undefined') dp = '.';

        // Get number and decimal part of n
        n = Number(n).toFixed(p).split('.');

        // Add thousands separator and decimal point (if requied):
        for (var iLen = n[0].length, i = iLen? iLen % 3 || 3 : 0, j = 0; i <= iLen; i+=3) {
            t.push(n[0].substring(j, i));
            j = i;
        }
        // Insert separators and return result
        return t.join(ts) + (n[1]? dp + n[1] : '');
    }

    $("#unsplitItemKPI").click(function(event){
        event.preventDefault();

        var checked = $('#detailkpi-table :checkbox:checked');
        if(checked.length == 0) {
            alert('Silakan pilih KPI terlebih dahulu.');
        }
        else if(checked.length !== 1)
        {
            alert('Pilih salah satu item KPI saja yang ingin di update.');
        }
        else
        {
            var id = checked.val();
            var url = "{{ route('backends.kpi.rencana.individu.unsplititem',':id') }}"; 
           
            $.ajax({
                url : url.replace(':id',id),
                type : 'GET',
                datatype : 'json',
                beforeSend : function(){
                    startLoader('#container');
                },
                success : function(resp){

                    stopLoader("#container");

                    if(resp.status == 'success'){
                        $("#unsplit").attr("did", id);
                        var html = '';
                        var no = 1;
                        var jumlah_bobot = 0;
                        var jumlah_target = 0;

                        for (var i = 0; i < resp.data.data.length; i++) 
                        {
                            html += '<tr>\
                                <td class="no-unsplit">'+no+'</td>\
                                <td class="">'+resp.data.data[i].DeskripsiKRA+'</td>\
                                <td class="">'+resp.data.data[i].DeskripsiKPI+'</td>\
                                <td class="">'+kraBawahan+'</td>\
                                <td class="">'+resp.data.data[i].Target12+'</td>\
                                <td class="">'+resp.data.data[i].Bobot+'</td> \
                            </tr>';

                            var kraBawahan = resp.data.data[i].IsKRABawahan == 1 ? 'IYA' : 'TIDAK';

                            if(resp.data.data[i].IDJenisAppraisal == 2){
                                jumlah_target += parseFloat(resp.data.data[i].Target12);
                            }else{
                                jumlah_target = parseFloat(resp.data.data[i].Target12);
                            }

                            jumlah_bobot += parseFloat(resp.data.data[i].Bobot);
                            no += 1;
                        }

                        var html2 = '';
                        var no2 = 1;

                        var kraBawahan2 = resp.data.data[0].IsKRABawahan == 1 ? 'IYA' : 'TIDAK';

                        html2 += '<tr>\
                            <td class="no-unsplit">'+no2+'</td>\
                            <td class="">'+resp.data.data[0].DeskripsiKRA+'</td>\
                            <td class="">'+resp.data.data[0].DeskripsiKPI+'</td>\
                            <td class="">'+kraBawahan2+'</td>\
                            <td class="">'+formatNumber(jumlah_target)+'</td>\
                            <td class="">'+formatNumber(jumlah_bobot)+'</td> \
                        </tr>';
                        no2 += 1;

                        $("#unsplit-item").html(html);
                        $("#normal-unsplit").html(html2);

                        $("#modal-unsplit").modal("show"); 
                    }
                    else
                    {
                        alert(resp.message);
                    }
                },
                error : function(jqXHR, exception){
                    stopLoader("#container");
                    errorHandling(jqXHR.status, exception); 
                }
            });
        }
    });

    $('body').delegate("#unsplit", "click", function(e){
        e.preventDefault();

        var id = $(this).attr("did");

        var url = '{{ url("kpi/rencana/individu") }}/'+id+'/storeunsplit';
           
        $.ajax({
            url : url.replace(':id',id),
            type : 'GET',
            datatype : 'json',
            beforeSend : function(){
                startLoader('.modal-content');
            },
            success : function(resp){
                stopLoader('.modal-content');

                if(resp.status == "success"){
                    alert(resp.message);
                    location.reload(); 
                }else{
                    alert(resp.message);
                }
            },
            error : function(jqXHR, exception){
                stopLoader('.modal-content');
                errorHandling(jqXHR.status, exception); 
            }
        });
    });

    function resetTable(){
        $("#layout-child").find("td").remove();
    }

    $('#updateItemKPI').click(function(event) {
        event.preventDefault();
        var checked = $('#detailkpi-table :checkbox:checked');
        if(checked.length == 0) {
            alert('Silakan pilih KPI terlebih dahulu.');
        }
        else if(checked.length !== 1)
        {
            alert('Pilih salah satu item KPI saja yang ingin di update.');
        }
        else {
            var id = checked.val();
            var url = "{{ route('backends.kpi.rencana.individu.edititem',':id') }}";
            window.location.href = url.replace(':id',id);
        }
    });

        function kodeAspek() {
            var kode = $('#IDKodeAspekKPI').val();

            if(kode == 2) {
                $('#Bobot').val(5).prop('readonly', true).prop('disabled', true);
                if('{{ $data['posisi'] }}' != 'DF') {
                    $('#s-option').prop('disabled', true);
                    $('#f-option').prop('disabled', true);
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


        $("body").delegate("#add-row", "click", function(e){
            e.preventDefault();
 
            var parent = $("#layout-parent").find("tr").clone();
            var child = $("#layout-child").append(parent);

            var buttonAction = child.find("button");
            //var inputPersentaseKRA = child.find("tr:last").find(".PersentaseKRA").inputPersentaseKRA.val(""); 

            buttonAction.removeClass("btn-success").addClass("btn-danger");
            buttonAction.attr("id", "").addClass("remove-row");
            buttonAction.html("<i class='fa fa-minus'></i>");

            numbering();
        });

        $("body").delegate(".remove-row", "click", function(e){
            e.preventDefault();

            $(this).closest("tr").remove();

            numbering(); 
        });

        $("body").delegate(".target", "keyup", function(){
            sumTargetResult();
        });

        $("body").delegate(".bobot", "keyup", function(){
             sumBobotResult();
        });

        $("body").delegate("#auto-target", "click", function(){
            if($(this).prop("checked") == true){
                autoCompleteTarget = true;
                setTarget();
                sumTargetResult();
            } 
            else {
                autoCompleteTarget = false;
            } 
        });

        function numbering(){
            var angka = 0; 
            $("#form-table-split").find(".numbering").each(function(){
                angka = angka + 1;
                $(this).closest("tr").find(".numbering").html(angka + '.')
            }); 

            setTarget();
            sumTargetResult();
            setBobot();
            sumBobotResult();
        }

        function sumTargetResult(){
            var jumlah = sumTarget(); 

            $("#target-min").html(jumlah.toFixed(2));
        }

        function sumTarget(){
            var jumlah = 0;
            var jenis = $('#jenisappraisal').val();
            var target = $("#target").val();

            if(jenis == 2){
                $("#form-table-split").find(".target").each(function(){ 
                    if($(this).val() != ''){
                        jumlah += parseFloat($(this).val());
                    } 
                });
            } else {
                jumlah += parseFloat(target);
            }

            return jumlah;
        }

        function sumBobotResult(){
            var jumlah = sumBobot(); 
            $("#bobot-min").html(jumlah.toFixed(2));
        }

        function sumBobot(){
            var jumlah = 0;
            $("#form-table-split").find(".bobot").each(function(){ 
                if($(this).val() != ''){
                    jumlah += parseFloat($(this).val());
                } 
            });

            return jumlah;
        }

        function setTarget(){
            var jenis = $('#jenisappraisal').val();
            var target = $("#target").val();

            if(jenis == 2) // kumulatif
            {
                var jumlah = $("#form-table-split").find(".target").length; 
                
                if(autoCompleteTarget == true){
                    $("#form-table-split").find(".target").each(function(){ 
                        $(this).val(parseFloat(target/jumlah).toFixed(2));
                        $(this).prop("readonly", false);
                    });
                } else {
                    if(!(jumlah < 2)){
                        $("#form-table-split").find(".target:last").val("");
                        $(this).prop("readonly", false);
                    }
                    
                }
            }else{

                $("#form-table-split").find(".target").each(function(){ 
                    $(this).val(target);
                    $(this).prop("readonly", true);
                });

            }
        } 

        function setBobot()
        {
            var bobot = $("#bobot").val();
            var jumlah = $("#form-table-split").find(".bobot").length; 

            $("#form-table-split").find(".bobot").each(function(){ 
                $(this).val(parseFloat(bobot/jumlah).toFixed(2)); 
            });
        }

        $(document).on("submit", "#form-split", function(e){
            e.preventDefault();

            var jenis = $('#jenisappraisal').val();
            var total_target = $("#target").val();
            var sum_target = sumTarget().toFixed(2);

            if(jenis == 2){
                if(parseFloat(sum_target) > parseFloat(total_target)){
                    $("#layout-total-target").addClass("bg-red");
                    alert('total target tidak boleh melibihi dari ' + total_target + ' - ' + sum_target);
                    return;
                }else if(parseFloat(sum_target) < parseFloat(total_target)){
                    $("#layout-total-target").removeClass("bg-red");
                } 
            }
             
            var id = $("#ID").val(); 

            $.ajax({
                url : '{{ url("kpi/rencana/individu") }}/'+id+'/storesplit',
                type : 'POST',
                data : new FormData(this),
                datatype : 'json',
                contentType : false,
                processData : false,
                beforeSend : function(){
                    startLoader('.modal-content');
                },
                success : function(resp){
                    if(resp.status == "success"){
                        alert(resp.message);
                        location.reload(); 
                    }else{
                        alert(resp.message);
                    }

                    stopLoader('.modal-content');
                },
                error : function(jqXHR, exception){
                    stopLoader('.modal-content');
                    errorHandling(jqXHR.status, exception); 
                }
            }); 
        });


        /*
        |--------------------------------------------------------------------------
        | Function Error handling ajax
        |--------------------------------------------------------------------------
        */   

        function errorHandling(jqXHR, exception) {
            if (jqXHR===0) {
                alert(' Koneksi terputus');
            }else if(jqXHR===404){

                alert(' request not found');
            }else if(jqXHR===500){

                alert(' internal server Error');
            }else if(exception==='parseerror'){

                alert('Request Json Parse failed');
            }else if(exception==='timeout'){

                alert('Timeout Error');
            }else if(exception==='abort'){

                alert('Ajax Request Aborted');
            }else{

                alert('error '+jqXHR.responseText);
            } 
        }


        function startLoader(selector){
            $(selector).waitMe({
                effect: 'ios',
                text : 'Loading...',
                bg: 'rgba(255,255,255,0.7)',
                color: 'rgb(51, 122, 183)',
                textPos: 'horizontal',
                maxSize:30,
                fontSize :'18px'
            }); 
        }

        function stopLoader(selector){
            $(selector).waitMe("hide");
        }

    </script>
@endsection
