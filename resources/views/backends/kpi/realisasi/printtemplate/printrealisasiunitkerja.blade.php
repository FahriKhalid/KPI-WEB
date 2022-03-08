@inject('targetparser', 'Pkt\Domain\Rencana\Services\TargetParserService')
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
        .table-font {
            font-size: 10pt;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        .table-header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
    <title>Pupuk Kaltim - Laporan Realisasi KPI Unit Kerja</title>
</head>
<body>
<htmlpageheader name="page-header">
    <h3>Realisasi KPI Unit Kerja</h3>
</htmlpageheader>
    <div class="table-header">
        Realisasi<br>
        Key Performance Indicator (KPI) Unit Kerja Periode {{ $data['periode']->NamaPeriodeKPI }} Tahun {{ $data['header']->Tahun }}<br>
        {{ $data['karyawan']->NamaKaryawan }} / {{ $data['karyawan']->NPK }} / {{ $data['karyawan']->organization->position->unitkerja->Deskripsi }}
    </div>
    <table class="table-font" width="100%" cellpadding="5">
        <thead>
        <tr style="background-color:black;">
            <th color="white">No. </th>
            <th color="white">Aspek KPI</th>
            <th color="white">KRA</th>
            <th color="white">KPI</th>
            <th color="white">Bobot</th>
            <th color="white">Satuan</th>
            <th color="white">Target {{ $data['periode']->KodePeriode }}</th>
            <th color="white">Realisasi Target</th>
            <th color="white">Persentase Realisasi</th>
            <th color="white">Sifat</th>
            <th color="white">Jenis KPI</th>
        </tr>
        </thead>
        <tbody>
        @php
            $no = 1; $pencapaian = [];
        @endphp
        @forelse($data['alldetail'] as $detail)
            @php
                $pencapaian[] = $detail->PersentaseRealisasi;
            @endphp
            <tr>
                <td style="border-bottom: 1px solid;">{{ $no++ }}</td>
                <td style="border-bottom: 1px solid;">{{ $detail->detilrencana->aspekkpi->AspekKPI }}</td>
                <td style="border-bottom: 1px solid;">{{ $detail->detilrencana->DeskripsiKRA }}</td>
                <td style="border-bottom: 1px solid;">{{ $detail->detilrencana->DeskripsiKPI }}</td>
                <td style="border-bottom: 1px solid;">{{ $detail->detilrencana->Bobot }}%</td>
                <td style="border-bottom: 1px solid;">{{ $detail->detilrencana->satuan->Satuan }}</td>
                <td style="border-bottom: 1px solid;">{{ $detail->detilrencana->{'Target'.$data['targetRealization']} }}</td>
                <td style="border-bottom: 1px solid;">{{ $detail->Realisasi }}</td>
                <td style="border-bottom: 1px solid;">{{ $detail->PersentaseRealisasi }}</td>
                <td style="border-bottom: 1px solid;">{{ $detail->detilrencana->jenisappraisal->JenisAppraisal }}</td>
                <td style="border-bottom: 1px solid;">{{ $detail->detilrencana->persentaserealisasi->PersentaseRealisasi }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="11" class="text-center">Tidak ada data detail realisasi KPI</td>
            </tr>
        @endforelse
        </tbody>

        <tfoot>
        <tr>
            <th colspan="8">Rata-Rata Pencapaian</th>
            <th>{{ \Pkt\Domain\Realisasi\Services\FinalValueService::calculateAverage($pencapaian) }}%</th>
        </tr>
        </tfoot>
    </table>
<htmlpagefooter name="page-footer">
    <hr>
    <small><table width="100%"><tr><td width="40%">PT Pupuk Kalimantan Timur</td><td width="20%" align="center">- {PAGENO} -</td><td width="40%" align="right">Realisasi Unit Kerja KPI</td></tr></table></small>
</htmlpagefooter>
</body>
