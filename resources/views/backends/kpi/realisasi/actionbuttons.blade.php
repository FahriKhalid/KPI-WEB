<a href="{{ route('backends.kpi.realisasi.individu.show', ['id' => $ID]) }}" class="btn btn-xs btn-info" title="Detail Realisasi KPI">
    <i class="fa fa-eye"></i>
</a>
@if($IDStatusDokumen == 4)
    <a target="Laporan Realisasi KPI Individu" href="{{ route('backends.kpi.realisasi.individu.print', ['id' => $ID]) }}" class="btn btn-xs btn-primary" title="Cetak KPI">
        <i class="fa fa-print"></i>
    </a>
@else
    @if(auth()->user()->UserRole->Role === 'Karyawan' || auth()->user()->UserRole->Role == 'Administrator')
        <a href="{{ route('backends.kpi.realisasi.individu.editdetail', ['id' => $ID]) }}" class="btn btn-xs btn-warning" title="Edit Detail KPI">
            <i class="fa fa-edit"></i>
        </a>
    @endif
@endif