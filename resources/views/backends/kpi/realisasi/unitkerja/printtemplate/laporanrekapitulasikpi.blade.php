@inject('unit',\Pkt\Domain\KPI\Services\RekapitulasiKPIExcelDownloadService)
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Unit Kerja</th>
            <th>Jumlah Karyawan</th>
            <th>Sudah Mengumpulkan</th>
            <th>Belum Mengumpulkan</th>
            <th>%</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    @php
        $total_karyawan_count = 0;
        $total_collected_count= 0;
    @endphp
    <tbody>
    @forelse($data[$unit::UNITKERJA] as $key=>$unitkerja)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{$unitkerja->Deskripsi}}</td>
            @php
            $karyawan_count = $unitkerja->karyawan_count;
            $total_karyawan_count += $karyawan_count;
            $collected_count = $unitkerja->collected_count;
            $total_collected_count += $collected_count;
            $uncollected_count  = $karyawan_count - $collected_count;
            @endphp
            <td>{{ $karyawan_count }}</td>
            <td>{{ $collected_count }}</td>
            <td>{{ $uncollected_count }}</td>
            <td>{{ round(($collected_count/$karyawan_count)*100,1,PHP_ROUND_HALF_DOWN) }}%</td>
        </tr>
    @empty
        <tr>Tidak ada data yang ditampilkan</tr>
    @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td>JUMLAH KARYAWAN</td>
            <td></td>
            <td>{{$total_karyawan_count}}</td>
            <td>{{$total_collected_count}}</td>
            <td>{{$total_karyawan_count - $total_collected_count}}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ round(($total_collected_count/$total_karyawan_count)*100,1,PHP_ROUND_HALF_DOWN) }}%</td>
            <td>{{ round((($total_karyawan_count - $total_collected_count)/$total_karyawan_count)*100,1,PHP_ROUND_HALF_DOWN) }}%</td>
            <td></td>
        </tr>
    </tfoot>
</table>