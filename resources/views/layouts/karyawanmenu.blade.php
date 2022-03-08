<ul class="nav navbar-nav navbar-left nav-ul">
    <li class="{{ checkActiveMenu('dashboard*') }}"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    @php
        $isUnitKerja = auth()->user()->karyawan->isUnitKerja(); 
    @endphp
    <li class="hidden-xs {{ checkActiveMenu('kpi/rencana*') }}">
        <a href="#" class="main-menu">Rencana KPI</a>
        <ul class="dropdown-submenu">
            <li class="{{ checkActiveMenu('kpi/rencana/individu*') }}">
                <a href="{{ route('backends.kpi.rencana.individu') }}">KPI Individu</a>
            </li>
            <li class="{{ checkActiveMenu('kpi/rencana/bawahanlangsung*') }}">
                <a href="{{ route('backends.kpi.rencana.individu.bawahanlangsung') }}">KPI Bawahan Langsung</a>
            </li>
            <li class="{{ checkActiveMenu('kpi/rencana/bawahantaklangsung*') }}">
                <a href="{{ route('backends.kpi.rencana.individu.bawahantaklangsung') }}">KPI Bawahan Tak Langsung</a>
            </li>
        </ul>
    </li>
    <li class="dropdown visible-xs">
        <a href="#" class="dropdown-toggle main-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Rencana KPI<span class="caret"></span></a>
        <ul class="dropdown-menu dropdown-mobile">
            <li class="{{ checkActiveMenu('kpi/rencana/individu*') }}">
                <a href="{{ route('backends.kpi.rencana.individu') }}">KPI Individu</a>
            </li>
            <li class="{{ checkActiveMenu('kpi/rencana/bawahanlangsung*') }}">
                <a href="{{ route('backends.kpi.rencana.individu.bawahanlangsung') }}">KPI Bawahan Langsung</a>
            </li>
            <li class="{{ checkActiveMenu('kpi/rencana/bawahantaklangsung*') }}">
                <a href="{{ route('backends.kpi.rencana.individu.bawahantaklangsung') }}">KPI Bawahan Tak Langsung</a>
            </li>
        </ul>
    </li>
    <li class="hidden-xs {{ checkActiveMenu('kpi/realisasi*') }}">
        <a href="#" class="main-menu">Realisasi KPI</a>
        <ul class="dropdown-submenu">
            <li class="{{ checkActiveMenu('kpi/realisasi/individu*') }}">
                <a href="{{route('backends.kpi.realisasi.individu')}}">Realisasi Individu</a>
            </li>
            @if($isUnitKerja)
                <li class="{{ checkActiveMenu('kpi/realisasi/unitkerja*') }}">
                    <a href="{{route('backends.kpi.realisasi.unitkerja')}}">Realisasi Unit Kerja</a>
                </li>
            @endif
            <li class="{{ checkActiveMenu('kpi/realisasi/bawahanlangsung*') }}">
                <a href="{{route('backends.kpi.realisasi.individu.bawahanlangsung')}}">Realisasi Bawahan Langsung</a>
            </li>
            <li class="{{ checkActiveMenu('kpi/realisasi/bawahantaklangsung*') }}">
                <a href="{{route('backends.kpi.realisasi.individu.bawahantaklangsung')}}">Realisasi Bawahan Tak Langsung</a>
            </li>
            <li class="{{ checkActiveMenu('kpi/realisasi/grafiknilai*').checkActiveMenu('kpi/realisasi/rekapitulasi*') }}">
                <a href="{{route('backends.kpi.realisasi.individu.grafiknilai')}}">Grafik Nilai {{ $isUnitKerja ? ' dan Rekapitulasi' : ''}} KPI</a>
            </li>
            @if($isUnitKerja)
                <li class="{{ checkActiveMenu('kpi/realisasi/validasi*') }}">
                    <a href="{{ route('backends.realisasi.validasi.unitkerja.index') }}">Validasi KPI</a>
                </li>
            @endif
        </ul>
    </li>
    <li class="dropdown visible-xs">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Realisasi KPI<span class="caret"></span></a>
        <ul class="dropdown-menu dropdown-mobile">
            <li class="{{ checkActiveMenu('kpi/realisasi/individu*') }}">
                <a href="{{route('backends.kpi.realisasi.individu')}}">Realisasi Individu</a>
            </li>
            @if($isUnitKerja)
                <li class="{{ checkActiveMenu('kpi/realisasi/unitkerja*') }}">
                    <a href="{{route('backends.kpi.realisasi.unitkerja')}}">Realisasi Unit Kerja</a>
                </li>
            @endif
            <li class="{{ checkActiveMenu('kpi/realisasi/bawahanlangsung*') }}">
                <a href="{{route('backends.kpi.realisasi.individu.bawahanlangsung')}}">Realisasi Bawahan Langsung</a>
            </li>
            <li class="{{ checkActiveMenu('kpi/realisasi/bawahantaklangsung*') }}">
                <a href="{{route('backends.kpi.realisasi.individu.bawahantaklangsung')}}">Realisasi Bawahan Tak Langsung</a>
            </li>
            <li class="{{ checkActiveMenu('kpi/realisasi/grafiknilai*') }}">
                <a href="{{route('backends.kpi.realisasi.individu.grafiknilai')}}">Grafik Nilai KPI</a>
            </li>
            @if($isUnitKerja)
                <li class="{{ checkActiveMenu('kpi/realisasi/validasi*') }}">
                    <a href="{{ route('backends.realisasi.validasi.unitkerja.index') }}">Validasi KPI</a>
                </li>
            @endif
        </ul>
    </li>
    <!-- <li class="{{ checkActiveMenu('kpi/pengembangan*') }}"><a href="{{ route('backends.kpi.rencanapengembangan') }}">Rencana Pengembangan</a></li>
    <li class="{{ checkActiveMenu('kpi/kamus*') }}"><a href="{{ route('backend.kpi.kamus') }}">Kamus KPI</a></li> -->
</ul>