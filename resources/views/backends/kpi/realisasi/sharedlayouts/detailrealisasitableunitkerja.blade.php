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
            <th>Jenis Rencana Pengembangan</th>
            <th>Rencana Pengembangan</th>
            <th>Sifat</th>
            <th>Jenis KPI</th>
        </tr>
        </thead>
        <tbody>
        @php
            $persentase = [];
        @endphp

        @forelse($data['alldetail'] as $detail)
            <tr {!! rowLabelRealization($detail->KonversiNilai) !!}>
                @php
                    $persentase[] = $detail->PersentaseRealisasi;
                @endphp
                <td></td>
                <td>{{ $detail->detilrencana->aspekkpi->AspekKPI }}</td>
                <td>{{ $detail->detilrencana->DeskripsiKRA }}</td>
                <td>{{ $detail->detilrencana->DeskripsiKPI }}</td>
                <td>{{ $detail->detilrencana->Bobot }}%</td>
                <td>{{ $detail->detilrencana->satuan->Satuan }}</td>
                <td>{{ numberDisplay($detail->detilrencana->{'Target'.$data['targetRealization']}) }}</td>
                <td>{{ numberDisplay($detail->Realisasi) }}</td>
                <td>{{ number_format($detail->PersentaseRealisasi, 2) }}</td>
                <td>{{ (! empty($detail->IDRencanaPengembangan)) ? $detail->rencanapengembangan->RencanaPengembangan : '-' }}</td>
                <td>{{ (! empty($detail->RencanaPengembangan)) ? $detail->RencanaPengembangan : '-' }}</td>
                <td>{{ $detail->detilrencana->jenisappraisal->JenisAppraisal }}</td>
                <td>{{ $detail->detilrencana->persentaserealisasi->PersentaseRealisasi }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="13" class="text-center">Tidak ada data detail realisasi KPI</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>