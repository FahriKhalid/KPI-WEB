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

        .table-header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
    <title>Pupuk Kaltim - Rencana KPI Individu</title>
</head>
<body>
<div>
    @php
        $NilaiKPI=0;
        $colspan=$data['target'];
    @endphp
    <div class="table-header">
        Rencana<br>
        Key Performance Indicator (KPI) Individu Tahun {{ $data['header']->Tahun }}<br>
        {{ $data['karyawan']->NamaKaryawan }} / {{ $data['header']->NPK }}
    </div>
    <table class="scaled-05" width="100%" cellpadding="5">
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
            <th color="white" rowspan="2">Target Tahunan</th>
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
        @foreach($data['alldetail'] as $aspekkpi=>$details)
            <tr>
                @for($j=1;$j<=$data['target']*2+8;$j++)
                    @if($j==1)
                        <td style="border: 1px solid;background-color: rgb(180, 180, 180);">
                            @if($aspekkpi==1)
                                Strategis
                            @elseif($aspekkpi==2 || $aspekkpi==3)
                                Rutin
                            @elseif($aspekkpi==4)
                                Task Force
                            @endif
                        </td>
                    @else
                        <td style="border: 1px solid;background-color: rgb(180, 180, 180);"></td>
                    @endif
                @endfor
            </tr>
            @php
                $totalBobot=0;
            @endphp
            @foreach($details as $detail)
                <tr style="border: 1px solid;">
                    <td style="border: 1px solid;"></td>
                    <td style="border: 1px solid;">{{$detail->DeskripsiKRA}}</td>
                    <td style="border: 1px solid;">{{$detail->DeskripsiKPI }}</td>
                    <td style="border: 1px solid;">{{ $detail->satuan->Satuan }}</td>
                    <td style="border-bottom: 1px solid;">{{ $detail->Bobot }}%</td>
                    @php $targetTahunan = 0 @endphp
                    @for($i=1; $i<=$data['target']; $i++)
                        <td style="border-bottom: 1px solid;">{{ $detail->{'Target'.$targetparser->targetCount($data['target'])[$i - 1]} }}</td>
                        <td style="border: 1px solid;">-</td>
                        @php $targetTahunan +=  $detail->{'Target'.$targetparser->targetCount($data['target'])[$i - 1]} @endphp
                    @endfor
                    <td style="border-bottom: 1px solid;">{{ $targetTahunan  }}</td>
                    <td style="border-bottom: 1px solid;">-</td>
                    <td style="border-bottom: 1px solid;">{{ $detail->kpiatasan->Keterangan or $detail->Keterangan }}</td>
                </tr>
                @php
                    $totalBobot+=$detail->Bobot ;
                    $NilaiKPI+=$detail->Bobot;
                @endphp
            @endforeach
        @endforeach
        </tbody>
    </table>
</div>
<htmlpagefooter name="page-footer">
    <hr>
    <small>
        <table width="100%">
            <tr>
                <td width="40%">PT Pupuk Kalimantan Timur</td>
                <td width="20%" align="center">- {PAGENO} -</td>
                <td width="40%" align="right">Rencana Individu KPI</td>
            </tr>
        </table>
    </small>
</htmlpagefooter>
</body>