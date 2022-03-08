@extends('layouts.app')
@include('vendor.loader.loader',['phase'=>'Menyiapkan...'])
@section('submenu')
    @include('layouts.submenu.realisasi')
@endsection

@section('content')
    <div class="container margin-bottom-15">
        <div class="row">
            <div class="col-md-6 col-sm-6 info-text-hi">
                <h4 class="blue-color">Laporan Rekapitulasi KPI</h4>
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
                        <li>
                            <a href="{{ route('backends.kpi.realisasi.individu.grafiknilai') }}">Grafik Nilai KPI</a>
                        </li>
                        <li class="active">
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
            <div class="col-md-6 col-sm-offset-3">
                <div class="panel panel-default panel-box panel-create">
                    <div class="panel-body">
                        <div class="col-sm-11 col-sm-offset-1">
                            <form id="rekapitulasi-form" method="get" action="{{route('backends.kpi.realisasi.individu.unduhrekapitulasi')}}">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Rekapitulasi KPI Individu</label>
                                    <div class="col-sm-12">
                                        <ul class="container-check">
                                            <li>
                                                <label><input type="checkbox" name="Individu" {{$data['Individu']?'checked':''}}> Tiap Individu</label>
                                            </li>
                                            <li>
                                                <label><input type="checkbox" name="UnitKerja" {{$data['UnitKerja']?'checked':''}}> Tiap Unit Kerja</label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Tahun</label>
                                    {!! Form::select('Tahun', $data['tahun'], $data['Tahun'], ['class' => 'form-control changeme','id'=>'Tahun','onchange'=>'tahun()']) !!}
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Periode KPI</label>
                                    {!! Form::select('IDJenisPeriode', [], '', ['class' => 'form-control changeme','id'=>'IDJenisPeriode']) !!}
                                </div>
                                <div class="text-right save-container">
                                    <button type="button submit" class="btn btn-default btn-yellow">
                                        Unduh
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        function tahun(){
            var tahun = $('#Tahun').val();
            var idjenisperiode = $('#IDJenisPeriode');
            $.ajax({
                url: "{{ route('backends.kpi.realisasi.individu.periode',':tahun') }}".replace(':tahun',tahun),
                dataType: 'json',
                success: function(data){
                    idjenisperiode.find('option').remove().end();
                    for (var i = 0; i < data.length; i++){
                        idjenisperiode.append(''+
                            '<option value="' + data[i].IDJenisPeriode + '">'+ data[i].jenisperiode.NamaPeriodeKPI+'</option>'
                        );
                    }
                }
            });
        }
        $('#rekapitulasi-form').ready(function () {
            tahun();
        });
    </script>
@endsection