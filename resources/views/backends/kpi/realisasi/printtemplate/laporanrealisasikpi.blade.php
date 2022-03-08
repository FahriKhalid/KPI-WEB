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
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        @page {
            header: page-header;
            footer: page-footer;
        }
        .table-font {
            font-size: 10px;
        }
        .table-header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
    <title>Pupuk Kaltim - Laporan Realisasi KPI Individu</title>
</head>
<body>
<htmlpageheader name="page-header">
    <h3>Realisasi KPI Individu</h3>
</htmlpageheader>
    <div class="table-header">
        Realisasi<br>
        Key Performance Indicator (KPI) Individu Tahun {{ $data['header']->Tahun }} Periode {{ $data['periode']->KodePeriode }}<br>
        {{ $data['karyawan']->NamaKaryawan }} / {{ $data['karyawan']->NPK }}
    </div>
    <table class="table-font" width="100%" cellpadding="5">
        <thead>
        <tr style="background-color:black;">
            <th color="white">No.</th>
            <th color="white">Aspek KPI</th>
            <th color="white">KRA</th>
            <th color="white">KPI</th>
            <th color="white">Bobot</th>
            <th color="white">Satuan</th>
            <th color="white">Target {{ $data['periode']->KodePeriode }}</th>
            <th color="white">Realisasi Target</th>
            <th color="white">Persentase Realisasi</th>
            <th color="white">Konversi Nilai</th>
            <th color="white">Nilai Akhir</th>
            <th color="white">Sifat</th>
            <th color="white">Jenis KPI</th>
        </tr>
        </thead>
        <tbody>
        @php
            $totalBobot = 0; $strategis = 0.00; $rutin = 0.00; $taskforce = 0.00; $totalKPI = 0.00; $arrTotalKPI = [];
            $aspek = ''; $no =1;
        @endphp
        @forelse($data['alldetail'] as $detail)
            @php
                $arrTotalKPI[] = $detail->NilaiAkhir;
            @endphp
            @if($detail->detilrencana->aspekkpi->AspekKPI == 'Strategis')
                @php
                    $strategis += $detail->NilaiAkhir;
                @endphp
            @elseif($detail->detilrencana->aspekkpi->AspekKPI == 'Rutin Struktural' || $detail->detilrencana->aspekkpi->AspekKPI == 'Rutin Operasional')
                @php
                    $rutin += $detail->NilaiAkhir;
                @endphp
            @else
                @php
                    $taskforce += $detail->NilaiAkhir;
                @endphp
            @endif
            @if(empty($aspek))
                @php
                    $aspek = $detail->detilrencana->IDKodeAspekKPI;
                @endphp
            @else
                @if($detail->detilrencana->IDKodeAspekKPI != $aspek)
                    <tr style="border: 1px solid;">
                        <td colspan="12">&nbsp;</td>
                    </tr>
                    @php
                        $aspek = $detail->detilrencana->IDKodeAspekKPI;
                    @endphp
                @endif
                @php
                    $aspek = $detail->detilrencana->IDKodeAspekKPI;
                @endphp
            @endif

            <tr style="border: 1px solid;">
                <td style="border: 1px solid;">{{ $no++ }}</td>
                <td style="border: 1px solid;">{{ $detail->detilrencana->aspekkpi->AspekKPI }}</td>
                <td style="border: 1px solid;">{{ $detail->detilrencana->DeskripsiKRA }}</td>
                <td style="border: 1px solid;">{{ $detail->detilrencana->DeskripsiKPI }}</td>
                <td style="border: 1px solid;">{{ $detail->detilrencana->Bobot }}%</td>
                <td style="border: 1px solid;">{{ $detail->detilrencana->satuan->Satuan }}</td>
                <td style="border: 1px solid;">{{ number_format($detail->detilrencana->{'Target'.$data['targetRealization']}, 2) }}</td>
                <td style="border: 1px solid;">{{ number_format($detail->Realisasi, 2) }}</td>
                <td style="border: 1px solid;">{{ $detail->PersentaseRealisasi }}</td>
                <td style="border: 1px solid;">{{ $detail->KonversiNilai }}</td>
                <td style="border: 1px solid;">{{ number_format($detail->NilaiAkhir, 2) }}</td>
                <td style="border: 1px solid;">{{ $detail->detilrencana->jenisappraisal->JenisAppraisal }}</td>
                <td style="border: 1px solid;">{{ $detail->detilrencana->persentaserealisasi->PersentaseRealisasi }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data detail rencana KPI</td>
            </tr>
        @endforelse
        </tbody>

        <tfoot>
        <tr>
            <th colspan="9">Nilai KPI Strategis</th>
            <th>{{ $strategis }}</th>
        </tr>
        <tr>
            <th colspan="9">Nilai KPI Rutin</th>
            <th>{{ $rutin }}</th>
        </tr>
        <tr>
            <th colspan="9">Nilai KPI Task Force</th>
            <th>{{ $taskforce }}</th>
        </tr>
        <tr>
            <th colspan="9">Total Nilai KPI</th>
            <th>{{ number_format(array_sum($arrTotalKPI), 2) }}</th>
        </tr>
        </tfoot>
    </table>
<htmlpagefooter name="page-footer">
    <hr>
    <small><table width="100%"><tr><td width="40%">PT Pupuk Kalimantan Timur</td><td width="20%" align="center">- {PAGENO} -</td><td width="40%" align="right">Realisasi {{$data['header']->IsUnitKerja?'Unit Kerja':'Individu'}} KPI</td></tr></table></small>
</htmlpagefooter>
</body>
