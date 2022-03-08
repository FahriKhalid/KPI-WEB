<ul class="nav navbar-nav navbar-left nav-ul">
    <li class="{{ checkActiveMenu('dashboard*') }}"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="{{ checkActiveMenu('kpi/rencana*') }}"><a href="{{ route('backends.kpi.rencana.individu') }}">Rencana KPI</a></li>
    <li class="{{ checkActiveMenu('kpi/realisasi*') }}"><a href="{{ route('backends.kpi.realisasi.individu') }}">Realisasi KPI</a></li>
    <li class="{{ checkActiveMenu('laporan/rencanapengembangan*') }}"><a href="{{ route('report.rencanapengembangan.index') }}">Rencana Pengembangan</a></li>
</ul>