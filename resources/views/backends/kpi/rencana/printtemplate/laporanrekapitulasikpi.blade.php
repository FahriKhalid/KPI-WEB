@inject('karyawan','\Pkt\Domain\KPI\Services\ReportingKPIExcelDownloadService')
<table>
    <thead>
    <tr>
        <th>No</th>
        <th>NPK SAP</th>
        <th>Nama Lengkap</th>
        <th>Grade</th>
        <th>Unit Kerja</th>
        <th>Jabatan</th>
        <th>Jumlah Item KPI</th>
        <th></th>
        <th></th>
        <th>Status</th>
        <th>Keterangan</th>
    </tr>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th>SR + Rutin</th>
        <th>TF</th>
        <th>Total</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @forelse($data[$karyawan::RENCANAINDIVIDU] as $key=>$item)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{$item->NPK}}</td>
            <td>{{$item->NamaKaryawan}}</td>
            <td>{{$item->organization->Grade}}</td>
            <td>{{$item->organization->position->unitkerja->Deskripsi}}</td>
            <td>{{$item->organization->position->PositionTitle}}</td>
            @php
                $rutinStrategi = 0;
                $taskForce = 0;
                if ($item->rencanakpi->count() > 0) {
                    $rutinStrategi = $item->rencanakpi[0]->detail()->nonTaskForce()->count();
                    $taskForce = $item->rencanakpi[0]->detail()->taskForce()->count();
                }
                $total = $rutinStrategi + $taskForce;
            @endphp
            <td>{{ $rutinStrategi }}</td>
            <td>{{ $taskForce }}</td>
            <td>{{ $total }}</td>
            <td>{{ ($item->rencanakpi->count() > 0) ? $item->rencanakpi[0]->statusdokumen->StatusDokumen : '-' }}</td>
            <td>{{ ($item->rencanakpi->count() > 0) ? $item->rencanakpi[0]->Keterangan : '-' }}</td>
        </tr>
    @empty
        <tr>Tidak ada data yang ditampilkan</tr>
    @endforelse
    </tbody>
</table>