<div class="margin-min-15" style="overflow-x: scroll; width: calc(100% + 30px);">
    <table id="detailrencana-table" class="table table-striped" width="2000px">
        <thead>
        <tr>
            <th>No.</th>
            <th>Aspek KPI</th>
            <th>KRA</th>
            <th>KPI</th>
            <th>Bobot</th>
            <th>Satuan</th>
            @for($i=1; $i<=$data['target']; $i++)
                <th>Target {{$data['periodeTarget']}} - {{ $i }}</th>
            @endfor
            <th>Keterangan</th>
            <th>Sifat</th>
            <th>Jenis KPI</th>
            <th>Sbg KPI Bawahan</th>
            <th>KPI dari atasan</th>
            <th>Created on</th>
        </tr>
        </thead>
        <tbody>
        @php
            $totalBobot = 0;
        @endphp
        @forelse($data['alldetail'] as $detail)
            <tr id="{{ $detail->ID }}">
                <td></td>
                <td>{{ $detail->aspekkpi->AspekKPI }}</td>
                <td>{{ $detail->DeskripsiKRA }}</td>
                <td>{{ $detail->DeskripsiKPI }}</td>
                <td>{{ $detail->Bobot }}%</td>
                <td>{{ $detail->satuan->Satuan }}
                @for($i=1; $i<=$data['target']; $i++)
                    <td>{{ (! is_null( $detail->{'Target'.$targetparser->targetCount($data['target'])[$i - 1]})) ? numberDisplay($detail->{'Target'.$targetparser->targetCount($data['target'])[$i - 1]}) : '-' }}</td>
                @endfor
                <td>{{ $detail->kpiatasan->Keterangan or $detail->Keterangan }}</td>
                <td>{{ $detail->jenisappraisal->JenisAppraisal }}</td>
                <td>{{ $detail->persentaserealisasi->PersentaseRealisasi }}</td></td>
                <td class="text-center">{!! $detail->IsKRABawahan ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>' !!}</td>
                @php
                    $totalBobot += $detail->Bobot;
                @endphp
                <td> 
                    {!! $detail->IDKRAKPIRencanaDetil != null ? 'Iya' : 'Tidak' !!}
                </td>
                <td>
                    {{ $detail->CreatedOn }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ 10 + $data['target'] }}" class="text-center">Tidak ada data detail rencana KPI</td>
            </tr>
        @endforelse
        </tbody>
        <tfoot>
        <tr>
            <th colspan="4">Total Bobot</th>
            <th>{{ $totalBobot }}%</th>
        </tr>
        <tr>
            <th colspan="4">Total Item KPI</th>
            <th>{{ $data['alldetail']->count() }}</th>
        </tr>
        </tfoot>
    </table>
</div>