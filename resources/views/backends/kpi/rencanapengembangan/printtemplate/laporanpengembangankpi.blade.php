@inject('gap','\Pkt\Domain\Realisasi\Services\GapValueCalculationService')
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400" rel="stylesheet">
    <style>
        thead{
            background-color: rgb(51, 122, 183);
            color: white;
        }
        h3{
            text-align: center;
            background-color: rgb(35,137,176);
            color: white;
        }
        @page {
            header: page-header;
            footer: page-footer;
        }
    </style>
    <title>Pupuk Kaltim - Laporan Rencana Pengembangan KPI {{$data['header']->IsUnitKerja?'Unit Kerja':'Individu'}}</title>
</head>
<body>
<htmlpageheader name="page-header">
    <h3>{{-- <img alt="Key Performance Indicator Logo" src="{{ asset('assets/img/logo_inlanding.png') }}" style=" height: 150%;">--}}PT Pupuk Kalimantan Timur  - KPI Online</h3>
</htmlpageheader>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 margin-top-30">
            <div class="panel panel-default panel-box panel-create">
                <div class="panel-body">
                    <table width="100%">
                        <tr><td bgcolor="grey" color="white">Rencana Pengembangan KPI {{$data['header']->IsUnitKerja?'Unit Kerja ':''}}- {{$data['periode']->jenisperiode->NamaPeriodeKPI}}</td> <td align="right" bgcolor="black" color="white">{{ strtoupper($data['header']->statusdokumen->StatusDokumen) }}</td></tr>
                    </table>
                    <div class="col-sm-6">
                        <div style="border-bottom: solid 1px;margin-top: 3px;">Data Karyawan</div>
                        <div class="panel-body">
                            <table width="100%">
                                <tr><td width="40%" align="right"><b>NPK</b></td><td>{{ $data['header']->NPK }}</td></tr>
                                <tr><td width="40%" align="right"><b>Nama Karyawan</b></td><td>{{ $data['karyawan']->NamaKaryawan }}</td> </tr>
                                <tr><td width="40%" align="right"><b>Jabatan</b></td><td>{{ $data['karyawan']->organization->position->PositionTitle }}</td></tr>
                            </table>
                        </div>
                        {{--</div>--}}
                    </div>
                    <div class="col-sm-6">
                        <div style="border-bottom: solid 1px;margin-top: 3px;">Data KPI</div>
                        <div class="panel-body">
                            <table width="100%">
                                <tr><td width="40%" align="right"><b>Tahun</b></td><td>{{ $data['header']->Tahun }}</td></tr>
                                <tr><td width="40%" align="right"><b>Periode</b></td><td>{{ $data['header']->periodeaktif->jenisperiode->KodePeriode }}</td></tr>
                                <tr><td width="40%" align="right"><b>Nilai Akhir / Nilai Validasi</b></td><td>{{ $data['header']->NilaiAkhir>4?4.00:$data['header']->NilaiAkhir }} / {{ !empty($data['header']->NilaiValidasi)?$data['header']->NilaiValidasi>4?4.00:$data['header']->NilaiValidasi:'Belum tervalidasi' }}</td> </tr>
                            </table>
                        </div>
                        {{--</div>--}}
                    </div>
                </div>
            </div>
            <pagebreak>
                <table width="100%">
                    <tr><td bgcolor="grey" color="white">Detail Rencana Pengembangan KPI {{$data['header']->IsUnitKerja?'Unit Kerja ':''}}- {{$data['periode']->jenisperiode->NamaPeriodeKPI}}</td><td align="right" bgcolor="black" color="white">{{ strtoupper($data['header']->statusdokumen->StatusDokumen) }}</td></tr>
                </table>
                <div class="margin-min-15">
                    <table class="table table-striped" width="100%" rotate="-90" style="margin-left: 10px;">
                        <thead>
                        <tr style="background-color:black ;border: solid white 2px;">
                            <th color="white">Judul Item KPI</th>
                            <th color="white">Target {{ $data['periode']->jenisperiode->KodePeriode }}</th>
                            <th color="white">Total Nilai Target</th>
                            <th color="white">Nilai Akhir (%)</th>
                            <th color="white">Gap</th>
                            <th color="white">Follow Up</th>
                            <th color="white">Rencana Pengembangan</th>
                            <th color="white">Keterangan</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $totalBobot = 0;
                            $strategis = 0.00;
                            $rutin = 0.00;
                            $taskforce = 0.00;
                            $totalKPI = 0.00;
                            $arrTotalKPI = [];
                        @endphp
                        @forelse($data['alldetail'] as $detail)
                            @php
                                $persentase = \Pkt\Domain\Realisasi\Services\TargetValueCalculationService::calculate($detail->detilrencana->{'Target'.$data['targetRealization']}, $detail->Realisasi);
                                $convertion = \Pkt\Domain\Realisasi\Services\ConvertionService::convert($persentase);
                                $final = \Pkt\Domain\Realisasi\Services\FinalValueService::calculate($convertion, $detail->detilrencana->Bobot);
                                $arrTotalKPI[] = $final;
                            @endphp
                            @if($detail->detilrencana->aspekkpi->AspekKPI == 'Strategis')
                                @php
                                    $strategis += $final;
                                @endphp
                            @elseif($detail->detilrencana->aspekkpi->AspekKPI == 'Rutin Struktural' || $detail->detilrencana->aspekkpi->AspekKPI == 'Rutin Operasional')
                                @php
                                    $rutin += $final;
                                @endphp
                            @else
                                @php
                                    $taskforce += $final;
                                @endphp
                            @endif
                            <tr {!! rowLabelRealization($convertion) !!}>
                                <td style="border-bottom: 1px solid;">{{ $detail->detilrencana->DeskripsiKPI }}</td>
                                <td style="border-bottom: 1px solid;">{{ $detail->detilrencana->{'Target'.$data['targetRealization']} }}</td>
                                <td style="border-bottom: 1px solid;">{{ number_format($persentase, 2) }}</td>
                                <td style="border-bottom: 1px solid;">{{ number_format($final/4*100,2)}}</td>
                                <td style="border-bottom: 1px solid;">{{ $gap::calculate($detail->detilrencana->{'Target'.$data['targetRealization']}, $detail->Realisasi) }}</td>
                                <td style="border-bottom: 1px solid;">{{ !empty($detail->detilrencana->pengembangan->RencanaPengembangan)?$detail->detilrencana->pengembangan->RencanaPengembangan:'-' }}</td>
                                <td style="border-bottom: 1px solid;">{{ !empty($detail->detilrencana->RencanaPengembangan)?$detail->detilrencana->RencanaPengembangan:'-' }}</td>
                                <td style="border-bottom: 1px solid;">{{ !empty($detail->detilrencana->pengembangan->Keterangan)?$detail->detilrencana->pengembangan->Keterangan:'-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data detail rencana KPI</td>
                            </tr>
                        @endforelse
                        </tbody>

                        <tfoot>
                        <tr>
                            <th colspan="3">Total</th>
                            <th>{{ number_format(array_sum($arrTotalKPI)/4*100, 2) }}</th>
                            <th>{{ $gap::calculatetotal($data['header']->ID) }}</th>

                        </tr>
                        <tr>
                            <th colspan="3">Total Nilai KPI</th>
                            <th>{{ number_format(array_sum($arrTotalKPI), 2)>4? '4.00':number_format(array_sum($arrTotalKPI), 2) }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </pagebreak>
        </div>
    </div>
</div>
<htmlpagefooter name="page-footer">
    <hr>
    <small><table width="100%"><tr><td width="40%">PT Pupuk Kalimantan Timur</td><td width="20%" align="center">- {PAGENO} -</td><td width="40%" align="right">Rencana Pengembangan KPI {{$data['header']->IsUnitKerja?'Unit Kerja':'Individu'}}</td></tr></table></small>
</htmlpagefooter>
</body>