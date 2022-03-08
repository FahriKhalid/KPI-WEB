<ul class="nav navbar-nav navbar-left nav-ul">
    <li class="{{ checkActiveMenu('dashboard*') }}"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    {{--<li class="{{ checkActiveMenu('hakakses')}}">--}}
        {{--<a href="{{route('backend.master.user.privilege')}}">Hak Akses</a>--}}
    {{--</li>--}}
    <li class="{{ checkActiveMenu('master*') }} hidden-xs">
        <a href="javascript:;">Master</a>
        <ul class="dropdown-submenu">
            <li class="{{ checkActiveMenu('master/unitkerja*') }}">
                <a href="{{ route('backend.master.unitkerja') }}">Unit Kerja</a>
            </li>
            <li  class="{{ checkActiveMenu('master/jabatan*') }}">
                <a href="{{ route('backend.master.jabatan') }}">Jabatan</a>
            </li>
            <li class="{{ checkActiveMenu('master/kompetensi*') }}">
                <a href="{{ route('backend.master.kompetensi') }}">Kompetensi</a>
            </li>
            <li class="{{ checkActiveMenu('master/karyawan*') }}">
                <a href="{{ route('backend.master.karyawan') }}">Karyawan</a>
            </li>
            <li class="{{ checkActiveMenu('master/user*') }}">
                <a href="{{ route('backend.master.user') }}">User</a>
            </li>
        </ul>
    </li>
    <li class="dropdown visible-xs">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Master <span class="caret"></span></a>
        <ul class="dropdown-menu dropdown-mobile">
            <li class="{{ checkActiveMenu('master/unitkerja*') }}">
                <a href="{{ route('backend.master.unitkerja') }}">Unit Kerja</a>
            </li>
            <li  class="{{ checkActiveMenu('master/jabatan*') }}">
                <a href="{{ route('backend.master.jabatan') }}">Jabatan</a>
            </li>
            <li class="{{ checkActiveMenu('master/kompetensi*') }}">
                <a href="{{ route('backend.master.kompetensi') }}">Kompetensi</a>
            </li>
            <li class="{{ checkActiveMenu('master/karyawan*') }}">
                <a href="{{ route('backend.master.karyawan') }}">Karyawan</a>
            </li>
            <li class="{{ checkActiveMenu('master/user*') }}">
                <a href="{{ route('backend.master.user') }}">User</a>
            </li>
        </ul>
    </li>
</ul>