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
        .table-font {
            font-size: 12px;
        }
        td {
            border: 1px solid;
        }
        .table-header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
    <title>Pupuk Kaltim - Laporan Rencana KPI Unit Kerja</title>
</head>
<body>
<htmlpageheader name="page-header">
    <h3>Rencana KPI Unit Kerja</h3>
</htmlpageheader>
    <div class="table-header">
        Rencana Unit Kerja<br>
        Key Performance Indicator (KPI) Unit Kerja Tahun {{ $data['header']->Tahun }}<br>
        {{ $data['karyawan']->NamaKaryawan }} / {{ $data['karyawan']->NPK }} / {{ $data['karyawan']->organization->position->unitkerja->Deskripsi }}
    </div>
    <table class="table-font" width="100%" cellpadding="5">
        <thead>
        <tr style="background-color:black ;">
            <th color="white">No.</th>
            <th color="white">Kelompok</th>
            <th color="white">KRA</th>
            <th color="white">KPI</th>
            <th color="white">Bobot</th>
            <th color="white">Satuan</th>
            @for($i=1; $i<=$data['target']; $i++)
                <th color="white">Target {{$data['periodeTarget']}} - {{ $i }}</th>
            @endfor
            <th color="white">Sifat</th>
            <th color="white">Jenis KPI</th>
        </tr>
        </thead>
        <tbody>
        @php
            $totalBobot = 0; $no = 1;
        @endphp
        @forelse($data['alldetail'] as $detail)
            <tr style="border: 1px solid;">
                <td>{{ $no++ }}</td>
                <td>{{ $detail->aspekkpi->AspekKPI }}</td>
                <td>{{ $detail->DeskripsiKRA }}</td>
                <td>{{ $detail->DeskripsiKPI }}</td>
                <td>{{ $detail->Bobot }}%</td>
                <td>{{ $detail->satuan->Satuan }}</td>
                @for($i=1; $i<=$data['target']; $i++)
                    <td>{{ $detail->{'Target'.$targetparser->targetCount($data['target'])[$i-1]} or '-' }}</td>
                @endfor
                <td>{{ $detail->jenisappraisal->JenisAppraisal }}</td>
                <td>{{ $detail->persentaserealisasi->PersentaseRealisasi }}</td>
                @php
                    $totalBobot += $detail->Bobot;
                @endphp
            </tr>
        @empty
            <tr style="border: 1px solid;">
                <td colspan="{{ 9 + $data['target'] }}" class="text-center">Tidak ada data detail rencana KPI</td>
            </tr>
        @endforelse
        </tbody>
        <tfoot>
        <tr>
            <th colspan="4">Total Item KPI</th>
            <th>{{ $data['alldetail']->count() }}</th>
        </tr>
        <tr>
            <th colspan="4">Total Bobot</th>
            <th>{{ $totalBobot }}%</th>
        </tr>
        </tfoot>
    </table>
<htmlpagefooter name="page-footer">
    <hr>
    <small>
        <table width="100%">
            <tr>
                <td width="40%">PT Pupuk Kalimantan Timur</td>
                <td width="20%" align="center">- {PAGENO} -</td>
                <td width="40%" align="right">Rencana Unit Kerja KPI</td>
            </tr>
        </table>
    </small>
</htmlpagefooter>
</body>