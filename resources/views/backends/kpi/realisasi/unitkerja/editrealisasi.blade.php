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
                        <a href="{{ route('backends.kpi.realisasi.unitkerja.editdetail', ['id' => $data['header']->ID]) }}">Realisasi KPI</a>
                    </li>
                    <li>
                        <a href="{{ route('backends.kpi.realisasi.unitkerja.document', ['id' => $data['header']->ID]) }}">Dokumen</a>
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
                            <div class="panel-title-box no-border">Form Realisasi Unit Kerja</div>
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
                                        <div class="col-sm-6">
                                            <div class="panel panel-primary margin-top-15">
                                                <div class="panel-heading" style="background-color: #337ab7">Atasan Langsung</div>
                                                <div class="panel-body">
                                                    <ul class="list-group">
                                                        <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['atasanLangsung']->NPK }}</span></li>
                                                        <li class="list-group-item"><b>Nama</b><span class="pull-right">{{ $data['atasanLangsung']->NamaKaryawan }}</span></li>
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
                                                        <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['atasanTakLangsung']->NPK }}</span></li>
                                                        <li class="list-group-item"><b>Nama</b><span class="pull-right">{{ $data['atasanTakLangsung']->NamaKaryawan }}</span></li>
                                                        <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ $data['header']->JabatanAtasanBerikutnya }}</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <form class="form-horizontal" method="post" action="{{ route('backends.kpi.realisasi.unitkerja.storenilai',['id' => $data['header']->ID]) }}">
                                {!! csrf_field() !!}
                                <div class="margin-min-15" style="overflow-x: scroll; width: calc(100% + 30px);">
                                    <table id="detailkpi-table" class="table table-striped" width="2000px">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Aspek KPI</th>
                                            <th>Sifat</th>
                                            <th>Jenis KPI</th>
                                            <th>KRA</th>
                                            <th>KPI</th>
                                            <th>Bobot</th>
                                            <th>Satuan</th>
                                            <th>Target - {{ $data['periode']->KodePeriode }}</th>
                                            <th>Realisasi Target</th>
                                            <th>Persentase Realisasi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $totalBobot = 0; $baris = 1; $strategis = 0.00; $rutin = 0.00; $taskforce = 0.00;
                                            $totalKPI = 0.00; $arrTotalPersentase = [];
                                        @endphp
                                        @forelse($data['alldetail'] as $detail)
                                            <tr>
                                                <td></td>
                                                <input type="hidden" name="idrealisasiitem[]" value="{{ $detail->ID }}">
                                                <td id="aspek{{$baris}}">{{ $detail->detilrencana->aspekkpi->AspekKPI }}</td>
                                                <td>{{ $detail->detilrencana->jenisappraisal->JenisAppraisal }}</td>
                                                <td>{{ $detail->detilrencana->persentaserealisasi->PersentaseRealisasi }}</td>
                                                <td>{{ $detail->detilrencana->DeskripsiKRA }}</td>
                                                <td>{{ $detail->detilrencana->DeskripsiKPI }}</td>
                                                <td id="bobot{{$baris}}">{{ $detail->detilrencana->Bobot }}%</td>
                                                <td>{{ $detail->detilrencana->satuan->Satuan }}</td>
                                                <input type="hidden" name="bobot[]" value="{{ $detail->detilrencana->Bobot }}">
                                                <input type="hidden" name="idaspekkpi[]" value="{{ $detail->detilrencana->IDKodeAspekKPI }}">
                                                <input type="hidden" name="IDPersentaseRealisasi[]" id="persentaserealisasi{{ $baris }}" value="{{ $detail->detilrencana->IDPersentaseRealisasi }}">
                                                <td id="item{{ $baris }}">{{ numberDisplay($detail->detilrencana->{'Target'.$data['targetRealization']}) }}</td>
                                                <input type="hidden" name="target[]" id="targetke-{{ $baris }}" value="{{ $detail->detilrencana->{'Target'.$data['targetRealization']} }}">
                                                <td>
                                                    <input type="number" step="any" min="0" name="realization[]" class="form-control" required onchange="realisasi({{$baris}},{{$data['target']}},{{$baris}})" id="target{{$baris}}realization{{$baris}}" value="{{ $detail->Realisasi }}">
                                                </td>
                                                @php
                                                    $persentase = \Pkt\Domain\Realisasi\Services\TargetValueCalculationService::calculate($detail->detilrencana->{'Target'.$data['targetRealization']}, $detail->Realisasi, $detail->detilrencana->IDPersentaseRealisasi);
                                                    $arrTotalPersentase[] = $persentase;
                                                @endphp
                                                <td id="kpi{{$baris}}">{{ number_format($persentase, 2) }}</td>
                                            </tr>
                                            @php
                                                $baris++;
                                            @endphp
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">Tidak ada data detail rencana KPI</td>
                                            </tr>
                                        @endforelse
                                        <input type="hidden" id="maxBaris" value="{{ $baris }}">
                                        </tbody>

                                        <tfoot>
                                        <tr>
                                            <th colspan="10">Rata-Rata Pencapaian</th>
                                            <th id="pencapaian">{{ round(\Pkt\Domain\Realisasi\Services\FinalValueService::calculateAverage($arrTotalPersentase), 2, PHP_ROUND_HALF_DOWN) }}%</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="text-right save-container margin-top-15">
                                    <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                                    <a href="{{ route('backends.kpi.realisasi.unitkerja') }}" class="btn btn-default btn-orange">Kembali</a>
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
    <script src="{{ asset('assets/js/realization.js') }}" type="text/javascript"></script>
    <script>

        $('#detailkpi-table').DataTable({
            sDom: 't',
            columnDefs: [{targets: [0], orderable: false}],
            pageLength: 100,
            order: [],
            fnRowCallback: function (nRow, aData, iDisplayIndex) {
                var index = iDisplayIndex +1;
                $('td:eq(0)',nRow).html(index);
                return nRow;
            }
        });

        function realisasi(id, max, row){
            // calculate target value
            var input = parseFloat($('#target'+id+'realization'+row).val());
            var detail = parseFloat($('#targetke-'+row).val());
            var idpersentaseRealisasi = $('#persentaserealisasi' + row).val();
            var persentaserealisasi = calculatePersentaseRealisasi(detail, input, idpersentaseRealisasi);
            $('#kpi'+row).html(persentaserealisasi);

            var konversi = convertion(persentaserealisasi);
            $('#konversi'+row).html(konversi);

            var bobot = parseFloat($('#bobot'+row).html());
            var akhir = nilaiAkhir(konversi, bobot);
            $('#nilaiakhir'+row).html(akhir);

            var maxBaris = parseFloat('{{ $data['alldetail']->count() }}');
            var persentaseSum = 0;
            for(i = 1; i <= maxBaris; i++) {
               persentaseSum += parseFloat($('#kpi' + i).html());
            }
            // calcultae pencapaian
            var pencapaian = persentaseSum / maxBaris;
            $('#pencapaian').html(parseFloat(Math.round((pencapaian)*100)/100).toFixed(2) + '%');
        }
    </script>
@endsection
