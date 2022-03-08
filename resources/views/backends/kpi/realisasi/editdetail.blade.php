@inject('targetparser', 'Pkt\Domain\Rencana\Services\TargetParserService')
@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.rencana')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 margin-top-15">
                <ul class="kpi-menu">
                    <li class="active">
                        <a href="{{ route('backends.kpi.realisasi.individu.editdetail', ['id' => $data['header']->ID]) }}">Realisasi KPI</a>
                    </li>
                    <li>
                        <a href="{{ route('backends.kpi.realisasi.individu.indexdocument', ['id' => $data['header']->IDKPIRencanaHeader]) }}">Dokumen</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 margin-top-30">
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="panel-title-box no-border">Data Karyawan</div>
                                @include('vendor.flash.message')
                            </div>
                            <div class="col-sm-12">
                                <div class="panel panel-primary" id="accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Data Karyawan</a>
                                        </h4>
                                    </div>
                                    <div id="collapse1" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <ul class="list-group">
                                                <li class="list-group-item"><b>Tahun</b><span class="pull-right">{{ $data['header']->Tahun }}</span></li>
                                                <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['header']->NPK }}</span></li>
                                                <li class="list-group-item"><b>Nama Karyawan</b><span class="pull-right">{{ $data['karyawan']->NamaKaryawan }}</span> </li>
                                                <li class="list-group-item"><b>Grade</b><span class="pull-right">{{ $data['header']->Grade }}</span> </li>
                                                <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ $data['karyawan']->organization->position->PositionTitle }}</span> </li>
                                                <li class="list-group-item"><b>Kode Unit Kerja</b><span class="pull-right">{{ $data['karyawan']->organization->position->KodeUnitKerja }}</span></li>
                                                <li class="list-group-item"><b>Unit Kerja</b><span class="pull-right">{{ $data['karyawan']->organization->position->unitkerja->Deskripsi }}</span> </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse-atasanlangsung">Atasan Langsung</a>
                                        </h4>
                                    </div>
                                    <div id="collapse-atasanlangsung" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <ul class="list-group">
                                                <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['atasanLangsung']->NPK }}</span></li>
                                                <li class="list-group-item"><b>Nama</b><span class="pull-right">{{ $data['atasanLangsung']->NamaKaryawan }}</span></li>
                                                <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ $data['header']->JabatanAtasanLangsung }}</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse-atasantaklangsung">Atasan Tak Langsung</a>
                                        </h4>
                                    </div>
                                    <div id="collapse-atasantaklangsung" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <ul class="list-group">
                                                <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['atasanTakLangsung']->NPK }}</span></li>
                                                <li class="list-group-item"><b>Nama</b><span class="pull-right">{{ $data['atasanTakLangsung']->NamaKaryawan }}</span></li>
                                                <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ $data['header']->JabatanAtasanBerikutnya }}</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 margin-top-30" style="overflow-x:auto;">
                                <form class="form-horizontal" method="post" action="{{ route('backends.kpi.realisasi.individu.storenilai',['id' => $data['header']->ID]) }}">
                                {!! csrf_field() !!}
                                    <table class="table table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">No.</th>
                                                <th rowspan="2">Aspek KPI</th>
                                                <th rowspan="2">Satuan</th>
                                                <th rowspan="2">Bobot</th>
                                                <th colspan="{{ $data['target'] }}" style="text-align: center">Target Rencana</th>
                                                <th colspan="{{ $data['target'] }}" style="text-align: center">Target Terealisasikan</th>
                                                <th colspan="{{ $data['target'] }}" style="text-align: center">Total Nilai Target</th>
                                                <th rowspan="2">Rata-rata Target</th>
                                                <th rowspan="2">Konversi Nilai</th>
                                                <th rowspan="2">Nilai Akhir</th>
                                            </tr>
                                            <tr>
                                                @for($i=1; $i<=$data['target']; $i++)
                                                <th>Target {{$data['periodeTarget']}} - {{ $i }}</th>
                                                @endfor

                                                @for($i=1; $i<=$data['target']; $i++)
                                                <th>Target realisasi {{ $i }}</th>
                                                @endfor

                                                @for($i=1; $i<=$data['target']; $i++)
                                                <th>KPI {{ $i }}</th>
                                                @endfor
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalBobot = 0; $baris = 1; $no = 1;
                                            @endphp
                                            @forelse($data['alldetail'] as $detail)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td id="aspek{{$baris}}">{{ $detail->aspekkpi->AspekKPI }}</td>
                                                    <td>{{ $detail->satuan->Satuan }}</td>
                                                    <td id="bobot{{$baris}}">{{ $detail->Bobot }}%</td>
                                                    @for($i=1; $i<=$data['target']; $i++)
                                                        <td id="detail{{$i}}row{{$baris}}">{{ $detail->{'Target'.$targetparser->targetCount($data['target'])[$i-1]} }}</td>
                                                    @endfor

                                                    @for($i=1; $i<=$data['target']; $i++)
                                                        <td><input class="double" type="number" required onchange="realisasi({{$i}},{{$data['target']}},{{$baris}})" id="target{{$i}}row{{$baris}}" {{ $detail->{'Target'.$targetparser->targetCount($data['target'])[$i-1]}==0?'disabled':'' }}>
                                                        </td>
                                                    @endfor

                                                    @for($i=1; $i<=$data['target']; $i++)
                                                        <td>
                                                            <div id="kpi{{$i}}row{{$baris}}"></div>
                                                        </td>
                                                    @endfor

                                                    @php
                                                        $totalBobot += $detail->Bobot;
                                                    @endphp
                                                    <td id="rata-rata{{$baris}}"></td>
                                                    <td id="konversi{{$baris}}"></td>
                                                    <td id="n_akhir{{$baris}}"></td>
                                                    @php
                                                        $baris = $baris+1; 
                                                    @endphp
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="{{ 9 + $data['target'] }}" class="text-center">Tidak ada data detail rencana KPI</td>
                                                </tr>
                                            @endforelse
                                            <input type="hidden" id="maxBaris" value="{{$baris}}">
                                        </tbody>
                                        
                                        <tfoot>
                                        <tr>
                                            <th colspan="10">Nilai KPI Strategis</th>
                                            <th id="strategis"></th>
                                        </tr>
                                        <tr>
                                            <th colspan="10">Nilai KPI Rutin Operasional</th>
                                            <th id="rutin"></th>
                                        </tr>
                                        <tr>
                                            <th colspan="10">Nilai KPI Struktural</th>
                                            <th id="struktural"></th>
                                        </tr>
                                        <tr>
                                            <th colspan="10">Nilai KPI Task Force</th>
                                            <th id="task_force"></th>
                                        </tr>
                                        <tr>
                                            <th colspan="10">Total nilai</th>
                                            <th id="rata_nilai"></th>
                                            <input type="hidden" id="rataNilaiInput" name="NilaiAkhir">
                                        </tr>
                                        </tfoot>
                                    </table>
                                    <div class="text-right save-container">
                                        <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
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
@endsection

@section('customjs')
    <script>
    var idDetail; //variable global untuk dapetin id detail
        $(function () {
            $('.double').keypress(
                function (evt) {
                if (evt.which != 8 && evt.which != 0 && evt.which != 46 && evt.which < 48 || evt.which > 57) {
                    evt.preventDefault();
                }
            });
        });


        function realisasi(id, max, row){
            var input = parseFloat($('#target'+id+'row'+row).val());
            var detail = parseFloat($('#detail'+id+'row'+row).html());
            var kpi = parseFloat(Math.round((input/detail)*100*100)/100).toFixed(2);
            $('#kpi'+id+'row'+row).html(kpi);

            var total=0;
            var tamp;
            var maks = 0;
            for(i = 1; i <= max; i++){
                tamp = parseFloat($('#kpi'+i+'row'+row).html());
                if(tamp){
                    maks = maks + 1;
                    total = total + tamp;
                }
            }
            var rata = parseFloat(Math.round((total/maks)*100)/100).toFixed(2);
            $('#rata-rata'+row).html(total);
            var konversi;
            if(kpi < 70) {
                konversi = 0;
            } else if(kpi >= 70 && kpi < 80) {
                konversi = 1;
            } else if(kpi >= 80 && kpi < 90) {
                konversi = 2;
            } else if(kpi >= 90 && kpi <= 100) {
                konversi = 3;
            } else if(kpi > 100) {
                konversi = 4;
            }

            $('#konversi'+row).html(konversi);
            var bobot = parseFloat($('#bobot'+row).html());
            var akhir = (konversi*bobot)/100;

            $('#n_akhir'+row).html(akhir);
            total = 0;
            var maxBaris = parseFloat($('#maxBaris').val());
            for(i = 1; i < maxBaris; i++) {
                tamp = parseFloat($('#n_akhir'+i).html());
                total = total + tamp;
            }
            $('#rata_nilai').html(parseFloat(Math.round((total)*100)/100).toFixed(2));
            $('#rataNilaiInput').val(parseFloat(Math.round((total)*100)/100).toFixed(2))
            var total1 = 0;
            var total2 = 0;
            var total3 = 0;
            var total4 = 0;
            var aspek;
            for(i = 1; i < maxBaris; i++) {
                aspek = $('#aspek'+i).html();
                if(aspek == 'Strategis') {
                    total1 = total1 + parseFloat($('#n_akhir'+i).html());
                } else if(aspek == 'Rutin Struktural') {
                    total2 = total2 + parseFloat($('#n_akhir'+i).html());
                } else if(aspek == 'Rutin Operasional') {
                    total3 = total3 + parseFloat($('#n_akhir'+i).html());
                } else if(aspek == 'Task Force') {
                    total4 = total4 + parseFloat($('#n_akhir'+i).html());
                }
            }
            $('#strategis').html(parseFloat(Math.round((total1)*100)/100).toFixed(2));
            $('#struktural').html(parseFloat(Math.round((total2)*100)/100).toFixed(2));
            $('#rutin').html(parseFloat(Math.round((total3)*100)/100).toFixed(2));
            $('#task_force').html(parseFloat(Math.round((total4)*100)/100).toFixed(2));
        }
    </script>
@endsection