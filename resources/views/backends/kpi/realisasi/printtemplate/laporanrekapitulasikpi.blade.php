@inject('karyawan','\Pkt\Domain\KPI\Services\RekapitulasiKPIExcelDownloadService')
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
            <th>Nilai Awal</th>
            <th></th>
            <th></th>
            <th>Nilai Akhir</th>
            <th>Perubahan Nilai</th>
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
            <th>SR + Rutin</th>
            <th>TF</th>
            <th>Total</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @forelse($data[$karyawan::INDIVIDU] as $key=>$item)
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
            @php
                $nilaiRutinStrategi = 0;
                $nilaiTaskForce = 0;
                $totalNilai = 0;
                $nilaiAkhir = 0;
                if ($item->realisasikpi->count() > 0) {
                    $nilaiRutinStrategi = $item->realisasikpi[0]->NilaiAkhirNonTaskForce;
                    $nilaiTaskForce = $item->realisasikpi[0]->NilaiAkhir - $item->realisasikpi[0]->NilaiAkhirNonTaskForce;
                    $totalNilai = $item->realisasikpi[0]->NilaiAkhir;
                    $nilaiAkhir = ($item->realisasikpi[0]->NilaiValidasiNonTaskForce === null) ? $item->realisasikpi[0]->NilaiAkhir : 0.00;
                }
            @endphp
            <td>{{ $nilaiRutinStrategi }}</td>
            <td>{{ $nilaiTaskForce }}</td>
            <td>{{ $totalNilai }}</td>
            <td>{{ $nilaiAkhir}}</td>
            <td>{{ $totalNilai == $nilaiAkhir ? 'TETAP':'BERUBAH'}}</td>
            <td>{{ $nilaiAkhir > 0 ?'OK': 'BELUM' }}</td>
            <td>{{ ($item->realisasikpi->count() > 0) ? $item->realisasikpi[0]->Keterangan : '-' }}</td>
        </tr>
        @empty
        <tr>Tidak ada data yang ditampilkan</tr>
    @endforelse
    </tbody>
</table>