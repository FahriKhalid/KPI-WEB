<ul class="nav navbar-nav navbar-left nav-ul">
    <li class="{{ checkActiveMenu('dashboard*') }}"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="{{ checkActiveMenu('kpi/rencana*') }}"><a href="{{ route('backends.kpi.rencana.individu') }}">Rencana KPI</a></li>
    <li class="{{ checkActiveMenu('kpi/realisasi*') }}"><a href="{{ route('backends.kpi.realisasi.individu') }}">Realisasi KPI</a></li>
    <li class="{{ checkActiveMenu('laporan*') }} hidden-xs" >
        <a href="javascript:;">Laporan KPI</a>
        <ul class="dropdown-submenu">
            <li class="{{ checkActiveMenu('laporan/rencana/kpiindividu*') }}"><a href="{{ route('report.rencana.kpiindividu.index') }}">Rencana KPI Individu</a> </li>
            <li class="{{ checkActiveMenu('laporan/realisasi/kpiindividu*') }}"><a href="{{ route('report.realisasi.kpiindividu.index') }}">Realisasi KPI Individu</a> </li>
            <li class="{{ checkActiveMenu('laporan/rencana/kpiunitkerja*') }}"><a href="{{ route('report.rencana.kpiunitkerja.index') }}">Rencana KPI Unit Kerja</a> </li>
            <li class="{{ checkActiveMenu('laporan/realisasi/kpiunitkerja*') }}"><a href="{{ route('report.realisasi.kpiunitkerja.index') }}">Realisasi KPI Unit Kerja</a> </li>
        </ul>
    </li>

    <li class="dropdown visible-xs">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Laporan <span class="caret"></span></a>
        <ul class="dropdown-menu dropdown-mobile">
            <li class="{{ checkActiveMenu('laporan/rencana/kpiindividu*') }}"><a href="{{ route('report.rencana.kpiindividu.index') }}">Rencana KPI Individu</a> </li>
            <li class="{{ checkActiveMenu('laporan/realisasi/kpiindividu*') }}"><a href="{{ route('report.realisasi.kpiindividu.index') }}">Realisasi KPI Individu</a> </li>
            <li class="{{ checkActiveMenu('laporan/rencana/kpiunitkerja*') }}"><a href="{{ route('report.rencana.kpiunitkerja.index') }}">Rencana KPI Unit Kerja</a> </li>
            <li class="{{ checkActiveMenu('laporan/realisasi/kpiunitkerja*') }}"><a href="{{ route('report.realisasi.kpiunitkerja.index') }}">Realisasi KPI Unit Kerja</a> </li>
        </ul>
    </li>
</ul>