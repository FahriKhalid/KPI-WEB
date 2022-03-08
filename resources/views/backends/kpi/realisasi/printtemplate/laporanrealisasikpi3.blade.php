@inject('targetparser', 'Pkt\Domain\Rencana\Services\TargetParserService')
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400" rel="stylesheet">
    <style>
        thead {
            background-color: rgb(51, 122, 183);
            color: white;
        }

        h3 {
            text-align: center;
            background-color: rgb(35, 137, 176);
            color: white;
        }

        @page {
            header: page-header;
            footer: page-footer;
        }

        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .scaled-05 {
            font-size: 10px;
        }

        s
    </style>
    <title>Pupuk Kaltim - Laporan Realisasi KPI Individu</title>
    <title>Pupuk Kaltim - Laporan Realisasi KPI {{$data['header']->IsUnitKerja?'Unit Kerja':'Individu'}}</title>
</head>
<body>
<htmlpageheader name="page-header">
    <h3>{{-- <img alt="Key Performance Indicator Logo" src="{{ asset('assets/img/logo_inlanding.png') }}" style=" height: 150%;">--}}
        PT Pupuk Kalimantan Timur - KPI Online</h3>
</htmlpageheader>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 margin-top-30">
            <table width="100%">
                <tr>
                    <td bgcolor="grey" color="white">Data Detail Realisasi

                </tr>
            </table>
            <div class="margin-min-15">
                @php
                    $NilaiKPI=0;
                    $colspan=$data['target'];
                @endphp
                <table class="table table-striped scaled-05" width="140%" rotate="-90" style="margin-right: 10px">
                    <thead>
                    <tr style="background-color:black ;">
                        <th color="white" rowspan="2">Aspek</th>
                        <th color="white" rowspan="2" width="15%">KRA</th>
                        <th color="white" rowspan="2" width="15%">KPI</th>
                        <th color="white" rowspan="2">Satuan</th>
                        <th color="white" rowspan="2">Bobot KPI</th>
                        @for($i=1;$i<=$data['target'];$i++)
                            <th color="white" colspan="2">Target {{ $data['periodeTarget'] }}
                                - {{ $i }}</th>
                        @endfor
                        <th color="white" rowspan="2">Target Tahun</th>
                        <th color="white" rowspan="2">Realisasi Tahunan</th>
                        <th color="white" rowspan="2">Penjelasan</th>
                    </tr>
                    <tr style="background-color:black ;">
                        @for($i=1;$i<=$data['target'];$i++)
                            <th color="white">Renc</th>
                            <th color="white">Real</th>
                        @endfor
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data['alldetail'] as $details)
                        @foreach($details->detail->sortBy('IDKodeAspekKPI')->groupBy('IDKodeAspekKPI') as $index=>$detailRencana)
                            <tr style="border: 1px solid;background-color: rgb(180, 180, 180);">
                                <td style="border: 1px solid;background-color: rgb(180, 180, 180);">
                                    @if($index == 1)
                                        Strategi
                                    @elseif($index == 2 || $index== 3)
                                        Rutin
                                    @elseif($index == 4)
                                        Task Force
                                    @endif
                                </td>
                                @for($j=1;$j<=$data['target']*2+8-1;$j++)
                                    <td style="border: 1px solid;background-color: rgb(180, 180, 180);"></td>
                                @endfor
                            </tr>
                            @php
                                $totalBobot=0;
                            @endphp
                            @foreach($detailRencana as $detailRencanaItem)
                                <tr style="border: 1px solid;">
                                    <td style="border: 1px solid;"></td>
                                    <td style="border: 1px solid;">{{$detailRencanaItem->DeskripsiKRA}}</td>
                                    <td style="border: 1px solid;">{{$detailRencanaItem->DeskripsiKPI }}</td>
                                    <td style="border: 1px solid;">{{ $detailRencanaItem->satuan->Satuan }}</td>
                                    <td style="border-bottom: 1px solid;">{{ $detailRencanaItem->Bobot }}%</td>
                                    @php $targetTahunan = 0;
                                        $RealisasiTahunan = 0;
                                        $i=1;
                                    @endphp
                                    @foreach($detailRencanaItem->realisasidetil->sortBy('IDPeriodeKPI') as $idPeriodeKPI=>$nilaiRealisasi)
                                        <td style="border: 1px solid;">{{ $detailRencanaItem->{'Target'.$targetparser->targetCount($data['target'])[$i - 1]} }}</td>
                                        <td style="border: 1px solid;">{{ $nilaiRealisasi->NilaiAkhir }}</td>
                                        @php $targetTahunan +=  $detailRencanaItem->{'Target'.$targetparser->targetCount($data['target'])[$i - 1]};
                                        $RealisasiTahunan +=  $nilaiRealisasi->NilaiAkhir;
                                        $i++;
                                        @endphp
                                    @endforeach
                                    <td style="border-bottom: 1px solid;">{{ $targetTahunan }}</td>
                                    <td style="border-bottom: 1px solid;">{{ $RealisasiTahunan}}</td>
                                    <td style="border-bottom: 1px solid;">{{ $detailRencanaItem->Keterangan }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<htmlpagefooter name="page-footer">
    <hr>
    <small>
        <table width="100%">
            <tr>
                <td width="40%">PT Pupuk Kalimantan Timur</td>
                <td width="20%" align="center">- {PAGENO} -</td>
                <td width="40%" align="right">Realisasi Individu KPI
                </td>
            </tr>
        </table>
    </small>
</htmlpagefooter>
</body>
{{dd($data)}}