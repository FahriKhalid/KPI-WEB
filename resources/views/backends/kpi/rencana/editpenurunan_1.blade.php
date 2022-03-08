@inject('targetparser', 'Pkt\Domain\Rencana\Services\TargetParserService')
@extends('layouts.app')

@php
    $abbrev = auth()->user()->abbreviation()!=null ? auth()->user()->abbreviation()->isUnitKerja():false;
@endphp

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
                    <li class="active">
                        <a href="{{ route('backends.kpi.rencana.individu.pennurunan', ['id' => $data['header']->ID]) }}">Penurunan KPI</a>
                    </li>
                    <li>
                        <a href="{{ route('backends.kpi.rencana.individu.indexdocument', ['id' => $data['header']->ID]) }}">Dokumen</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <form class="form-horizontal" action="{{ route('backends.kpi.rencana.individu.penurunan.update', ['id' => $data['header']->ID, 'idcascade' => $data['cascadeitem']->ID]) }}" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="_method" value="put">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 margin-top-30">
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="border-bottom-container margin-bottom-15">
                                    @include('vendor.flash.message')
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">Data Karyawan</div>
                                        <div class="panel-body">
                                            <ul class="list-group">
                                                <li class="list-group-item"><strong>Tahun</strong><span class="pull-right">{{ $data['header']->Tahun }}</span></li>
                                                <li class="list-group-item"><strong>NPK</strong><span class="pull-right">{{ $data['karyawan']->NPK }}</span></li>
                                                <li class="list-group-item"><strong>Nama Karyawan</strong><span class="pull-right">{{ $data['karyawan']->NamaKaryawan }}</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="panel-title-box no-border">Penurunan KPI</div>
                            </div>
                            <div class="col-sm-11 col-sm-offset-1 margin-bottom-15">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">KPI</label>
                                    <div class="col-sm-7">
                                        <select class="form-control selectbox" id="selectkpiatasan" name="IDKPIAtasan">
                                            <option value="">--- Pilih KPI ---</option>
                                            @foreach($data['items'] as $item)
                                                <option value="{{ $item->ID }}" {{ ($data['cascadeitem']->IDKPIAtasan == $item->ID) ? 'selected="selected"' : '' }}>{{ $item->DeskripsiKPI }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Bawahan</label>
                                    <div class="col-sm-7">
                                        <select class="form-control selectbox" name="NPKBawahan">
                                            <option value="">--- Pilih Bawahan ---</option>
                                            @foreach($data['bawahan'] as $bawahan)
                                                <option value="{{ $bawahan->NPK }}" {{ ($data['cascadeitem']->NPKBawahan == $bawahan->NPK) ? 'selected="selected"' : '' }}>({{ $bawahan->NPK }}) {{ $bawahan->NamaKaryawan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" id="IDJenisAppraisal" value="{{ $data['cascadeitem']->detailkpi->IDJenisAppraisal }}">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Presentase KRA</label>
                                    <div class="col-sm-3">
                                        <input type="number" step="any" class="form-control" name="PersentaseKRA" id="PersentaseKRA" placeholder="Persentase KRA (%)" value="{{ $data['cascadeitem']->PersentaseKRA }}">
                                    </div>
                                </div>
                                @for($x = 1; $x <= $data['target']; $x++)
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Target {{$data['periodeTarget']}} - {{$x}}</label>
                                        <div class="col-sm-5">
                                            <input type="number" step="any" lang="en" pattern="-?[0-9]+[\,.]*[0-9]+" name="Target{{ $targetparser->targetCount($data['target'])[$x-1] }}" id="Target{{ $targetparser->targetCount($data['target'])[$x-1] }}" class="form-control double" placeholder="Target {{$x}}" value="{{ $data['cascadeitem']->{'Target'.$targetparser->targetCount($data['target'])[$x-1]} }}" data-targetoriginal{{ $targetparser->targetCount($data['target'])[$x-1] }}="{{ $data['cascadeitem']->detailkpi->{'Target'.$targetparser->targetCount($data['target'])[$x-1]} }}">
                                        </div>
                                    </div>
                                @endfor
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Keterangan</label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" name="Keterangan" rows="3" placeholder="Keterangan Penurunan KPI (Opsional)">{{ $data['cascadeitem']->Keterangan }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-7 save-container">
                                        <button type="submit" class="btn btn-default btn-blue">Update</button>
                                        <a href="{{ route('backends.kpi.rencana.individu.pennurunan', ['id' => $data['header']->ID]) }}" class="btn btn-default btn-orange">Batal</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="border-bottom-container margin-bottom-15"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('customjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
    <script>
        $('.selectbox').select2();

        $('#selectkpiatasan').change(function() {
            var idkpi = $(this).val();
            var apiurl = "{{ route('backends.kpi.rencana.individu.penurunan.apidetailkpi', [':iddetailrencana']) }}";
            if (idkpi != '') {
                $.get(apiurl.replace(':iddetailrencana', idkpi), function(data) {
                    $('#IDJenisAppraisal').val(data.IDJenisAppraisal);
                    for(i=1;i<=12;i++) {
                        var target = $('#Target' + i);
                        if (target != null) {
                            target.val(data['Target' + i]);
                            target.attr('data-targetoriginal'+i, data['Target' + i]);
                            var result = calculateTargetByPercentage(data['Target' + i], $('#PersentaseKRA').val(), data.IDJenisAppraisal);
                            target.val(result);
                        }
                    }
                });
            } else {
                $('#IDJenisAppraisal').val('');
                for(i=1;i<=12;i++) {
                    var target = $('#Target' + i);
                    if (target != null) {
                        target.val('');
                        target.removeAttr('data-targetoriginal'+i);
                    }
                }
            }
        });

        $('#PersentaseKRA').keyup(function() {
            if ($(this).val() <= 100) {
                for(i=1;i<=12;i++) {
                    var target = $('#Target' + i);
                    if (target !== null && target.val() !== '') {
                        var result = calculateTargetByPercentage(target.data('targetoriginal'+i), $(this).val(), $('#IDJenisAppraisal').val());
                        target.val(result);
                    }
                }
            } else {
                alert('Isian persentase KRA tidak boleh melebihi dari 100%.');
            }
        });

        function calculateTargetByPercentage(target, percentageKRA, jenisappraisal)
        {
            if (percentageKRA != '') {
                if (jenisappraisal == 2) { //kumulatif harus dibagi sesuai persentase
                    return (target / 100) * percentageKRA;
                }
            }
            return target;
        }
    </script>
@endsection