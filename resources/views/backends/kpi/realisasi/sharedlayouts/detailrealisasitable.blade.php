<div class="margin-min-15" style="overflow-x: scroll; width: calc(100% + 30px);">
    <table id="detailrealisasi-table" class="table table-striped" width="2000px">
        <thead>
        <tr>
            <th>No.</th>
            <th>Aspek KPI</th>
            <th>KRA</th>
            <th>KPI</th>
            <th>Bobot</th>
            <th>Satuan</th>
            <th>Target {{ $data['periode']->KodePeriode }}</th>
            <th>Realisasi Target</th>
            <th>Persentase Realisasi</th>
            <th>Konversi Nilai</th>
            <th>Nilai Akhir</th>
            <th>Jenis Rencana Pengembangan</th>
            <th>Rencana Pengembangan</th>
            <th>Sifat</th>
            <th>Jenis KPI</th>
        </tr>
        </thead>
        <tbody>
        @php
            $totalBobot = 0;$strategis = 0.00; $rutin = 0.00; $taskforce = 0.00; $totalKPI = 0.00; $arrTotalKPI = [];
        @endphp
        @forelse($data['alldetail'] as $detail)
            @php
                $final = $detail->NilaiAkhir;
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
            <tr {!! rowLabelRealization($detail->KonversiNilai) !!}>
                <td></td>
                <td>{{ $detail->detilrencana->aspekkpi->AspekKPI }}</td>
                <td>{{ $detail->detilrencana->DeskripsiKRA }}</td>
                <td>{{ $detail->detilrencana->DeskripsiKPI }}</td>
                <td>{{ $detail->detilrencana->Bobot }}%</td>
                <td>{{ $detail->detilrencana->satuan->Satuan }}</td>
                <td>{{ numberDisplay($detail->detilrencana->{'Target'.$data['targetRealization']}) }}</td>
                <td>{{ numberDisplay($detail->Realisasi) }}</td>
                <td>{{ number_format($detail->PersentaseRealisasi, 2) }}</td>
                <td>{{ (isset($detail->KonversiNilai)) ? number_format(rtrim($detail->KonversiNilai), 2) : '' }}</td>
                <td>{{ number_format($detail->NilaiAkhir, 2) }}</td>
                <td>{{ (! empty($detail->IDRencanaPengembangan)) ? $detail->rencanapengembangan->RencanaPengembangan : '-' }}</td>
                <td>{{ (! empty($detail->RencanaPengembangan)) ? $detail->RencanaPengembangan : '-' }}</td>
                <td>{{ $detail->detilrencana->jenisappraisal->JenisAppraisal }}</td>
                <td>{{ $detail->detilrencana->persentaserealisasi->PersentaseRealisasi }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="12" class="text-center">Tidak ada data detail rencana KPI</td>
            </tr>
        @endforelse
        </tbody>

        <tfoot>
        <tr>
            <th colspan="10">Nilai KPI Strategis</th>
            <th>{{ $strategis }}</th>
        </tr>
        <tr>
            <th colspan="10">Nilai KPI Rutin</th>
            <th>{{ $rutin }}</th>
        </tr>
        <tr>
            <th colspan="10">Nilai KPI Task Force</th>
            <th>{{ $taskforce }}</th>
        </tr>
        <tr>
            <th colspan="10">Total Nilai KPI</th>
            <th>{{ number_format(array_sum($arrTotalKPI), 2) }}</th>
        </tr>
        </tfoot>
    </table>
</div>