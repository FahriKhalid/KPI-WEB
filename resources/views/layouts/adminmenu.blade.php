<ul class="nav navbar-nav navbar-left nav-ul">
    <li class="{{ checkActiveMenu('dashboard*') }}"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="{{ checkActiveMenu('kpi/rencana*') }}"><a href="{{ route('backends.kpi.rencana.individu') }}">Rencana KPI</a></li>
    <li class="{{ checkActiveMenu('kpi/realisasi*') }}"><a href="{{ route('backends.kpi.realisasi.individu') }}">Realisasi KPI</a></li>
    <li class="{{ checkActiveMenu('kpi/kamus*') }}"><a href="{{ route('backend.kpi.kamus') }}">Kamus KPI</a></li>
    <li class="{{ checkActiveMenu('kpi/info*') }}"><a href="{{ route('backend.kpi.info') }}">Info KPI</a></li>
    <li class="{{ checkActiveMenu('pengaturan*') }} hidden-xs">
        <a href="javascript:;">Pengaturan</a>
        <ul class="dropdown-submenu">
            <li class="{{checkActiveMenu('pengaturan/periodeaktif*')}}">
                <a href="{{ route('backend.master.periodeaktif') }}">Periode Aktif</a>
            </li>
            <li class="{{ checkActiveMenu('pengaturan/validationmatrix*') }}"><a href="{{ route('validationmatrix.index') }}">Matriks Validasi</a></li>
            <li class="{{ checkActiveMenu('pengaturan/narration*') }}"><a href="{{ route('narration') }}">Narasi Beranda</a></li>
            <li class="{{ checkActiveMenu('pengaturan/faq*') }}"><a href="{{ route('faq') }}">FAQ</a></li>
        </ul>
    </li>

    <li class="dropdown visible-xs">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pengaturan <span class="caret"></span></a>
        <ul class="dropdown-menu dropdown-mobile">
            <li class="{{checkActiveMenu('pengaturan/periodeaktif*')}}">
                <a href="{{ route('backend.master.periodeaktif') }}">Periode Aktif</a>
            </li>
            <li class="{{ checkActiveMenu('pengaturan/validationmatrix*') }}"><a href="{{ route('validationmatrix.index') }}">Matriks Validasi</a></li>
            <li class="{{ checkActiveMenu('pengaturan/narration*') }}"><a href="{{ route('narration') }}">Narasi Beranda</a></li>
            <li class="{{ checkActiveMenu('pengaturan/faq*') }}"><a href="{{ route('faq') }}">FAQ</a></li>
        </ul>
    </li>
</ul>