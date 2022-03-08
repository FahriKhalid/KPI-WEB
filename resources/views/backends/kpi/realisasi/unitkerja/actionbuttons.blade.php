<a href="{{ route('backends.kpi.realisasi.unitkerja.show', ['id' => $ID]) }}" class="btn btn-xs btn-info" title="Detail Realisasi KPI">
    <i class="fa fa-eye"></i>
</a>
@if(\auth()->user()->abbreviation()!=null ?\auth()->user()->abbreviation()->isUnitKerja():false)
    @if($IDStatusDokumen == 4)
        <a target="Laporan Realisasi KPI Unit Kerja" href="{{ route('backends.kpi.realisasi.unitkerja.print', ['id' => $ID]) }}" class="btn btn-xs btn-primary" title="Cetak KPI">
            <i class="fa fa-print"></i>
        </a>
    @else
    <a href="{{ route('backends.kpi.realisasi.unitkerja.editdetail', ['id' => $ID]) }}" class="btn btn-xs btn-warning" title="Edit Realisasi Unit Kerja">
        <i class="fa fa-edit"></i>
    </a>
    @endif
@endif