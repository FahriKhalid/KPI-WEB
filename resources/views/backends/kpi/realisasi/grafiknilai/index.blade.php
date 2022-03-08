@extends('layouts.app')
@include('vendor.loader.loader',['phase'=>'Menyiapkan...'])
@section('submenu')
    @include('layouts.submenu.realisasi')
@endsection

@section('content')
    <script type="text/javascript" src="{{ asset('assets/js/jquery-2.2.4.min.js') }}"></script>
    {!! Charts::assets(['google', 'chartjs']) !!}
    <div class="container margin-bottom-15">
        <div class="row">
            <div class="col-md-6 col-sm-6 info-text-hi">
                <h4 class="blue-color">Grafik Nilai KPI</h4>
            </div>
        </div>
    </div>
    @php
        $abbrev = auth()->user()->abbreviation()!=null ? auth()->user()->abbreviation()->isUnitKerja():false;
    @endphp
    @if($abbrev)
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 margin-top-15">
                <ul class="kpi-menu">
                    <li class="active">
                        <a href="{{ route('backends.kpi.realisasi.individu.grafiknilai') }}">Grafik Nilai KPI</a>
                    </li>
                    <li>
                        <a href="{{  route('backends.kpi.realisasi.individu.rekapitulasi') }}">Laporan Rekapitulasi KPI</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @endif
    <div class="container">
        <div class="row">
            @include('vendor.flash.message')
            <div class="col-md-6 col-sm-12">
                <div class="panel panel-default panel-box panel-create">
                    <div class="panel-body">
                        <div class="col-sm-11 col-sm-offset-1">
                            <form id="grafiknilai-form" method="get" action="{{route('backends.kpi.realisasi.individu.findgrafiknilai')}}">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <label class="control-label">Realisasi :</label>
                                    <ul class="container-check">
                                        <li>
                                            <input type="radio" id="individu" name="IsUnitKerja" value="0" {{isset($data['_IsUnitKerja'])?($data['_IsUnitKerja']?'':'checked'):'checked'}}>
                                            <label for="individu">Individu</label>
                                            <div class="check"><div class="inside"></div></div>
                                        </li>
                                        <li>
                                            <input type="radio" id="unitkerja" name="IsUnitKerja" value="1" {{isset($data['_IsUnitKerja'])?($data['_IsUnitKerja']?'checked':''):''}}>
                                            <label for="unitkerja">Unit Kerja</label>
                                            <div class="check"></div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Unit Kerja</label>
                                    <input class="form-control" type="text" value="{{$data['unitkerja']}}" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Jenis KPI :</label>
                                        <ul class="container-check">
                                            <li>
                                                <input type="radio" id="s-option" name="IsBawahan" value="0" {{isset($data['isbawahan'])?!$data['isbawahan']:old('IsBawahan')==false?'checked':''}}>
                                                <label for="s-option">Individu</label>
                                                <div class="check"><div class="inside"></div></div>
                                            </li>
                                            <li>
                                                <input type="radio" id="f-option" name="IsBawahan" value="1" {{isset($data['isbawahan'])?$data['isbawahan']:old('IsBawahan')==true?'checked':''}}>
                                                <label for="f-option">Bawahan</label>
                                                <div class="check"></div>
                                            </li>
                                        </ul>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" id="LabelNPK">Nama</label>
                                    {!! Form::select('NPK', ['' => '-- Pilih Nama --'] + $data['bawahan'], $data['_bawahan'], ['class' => 'form-control changeme','id'=>'NPK']) !!}
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Tahun</label>
                                    {!! Form::select('Tahun', ['' => '-- Pilih Tahun --'] + $data['tahun'], $data['_tahun'], ['class' => 'form-control changeme','id'=>'Tahun']) !!}
                                </div>
                                {{--<div class="form-group">--}}
                                    {{--<label class="control-label">Periode Awal</label>--}}
                                    {{--{!! Form::select('PeriodeAwal', ['' => '-- Pilih Periode Awal --']  + ['a','b'], '', ['class' => 'form-control changeme','id'=>'PeriodeAwal']) !!}--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                    {{--<label class="control-label">Periode Akhir</label>--}}
                                    {{--{!! Form::select('PeriodeAkhir', ['' => '-- Pilih Periode Akhir --'] + ['a','b'], '', ['class' => 'form-control changeme','id'=>'PeriodeAkhir']) !!}--}}
                                {{--</div>--}}
                                <div class="text-right save-container">
                                    <button type="button submit" class="btn btn-default btn-yellow">
                                        Tampilkan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="panel panel-default panel-chart panel-box">
                    <div class="panel-body">
                        <div class="col-sm-6">
                            <div class="panel-title-box">Total data</div>
                        </div>
                        <div class="col-sm-6 text-right">
                            <div class="panel-title-box blue-color">{{count($data['count'])}}</div>
                        </div>
                        {{--<canvas id="canvas"></canvas>--}}
                        {!! $data['chart']->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script type="text/javascript" src="{{ asset('assets/js/Chart.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script>
        var config = {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: "Key Performance Indicator",
                    fill: false,
                    lineTension: 0,
                    backgroundColor: window.chartColors.blue,
                    borderColor: window.chartColors.blue,
                    pointBorderColor: "#f67a21",
                    pointBackgroundColor: "#f67a21",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "#f67a21",
                    pointBorderWidth: 0.5,
                    data: [],
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:false,
                    text:'Chart.js Line Chart'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Periode'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Nilai'
                        }
                    }]
                }
            }
        };
        function disablecontent() {
            $('#LabelNPK').hide();
            $('#NPK').removeAttr('required').hide();
        }
        function enablecontent() {
            $('#LabelNPK').show();
            $('#NPK').show().prop("required", true);
        }
        window.onload = function() {
//            var ctx = document.getElementById("canvas").getContext("2d");
//            window.myLine = new Chart(ctx, config);
            var IsBawahan = $("input[name='IsBawahan']:checked").val();
            if(IsBawahan==0){
                disablecontent();
            }else {
                enablecontent();
            }
        };
        $("input[name='IsBawahan']").change(function () {
            if($("input[name='IsBawahan']:checked").val()==0){
                disablecontent();
            }else {
                enablecontent();
            }
        });

    </script>


@endsection