<div class="submenu">
    <ul>
        <li class="">
            <a href="#">Direktorat</a>
        </li>
        <li class="{{ checkActiveMenu('master/karyawan*') }}">
            <a href="{{ route('backend.master.karyawan') }}">Karyawan</a>
        </li>
        <li class="{{ checkActiveMenu('master/organizationalAssignment*') }}">
            <a href="{{ route('backend.master.organizationalAssignment') }}">Penugasan</a>
        </li>
        <li>
            <a href="{{ route('backend.master.unitkerja') }}">Unit Kerja</a>
        </li>
        <li  class="{{ checkActiveMenu('master/jabatan*') }}">
            <a href="{{ route('backend.master.jabatan') }}">Jabatan</a>
        </li>
        <li class="{{ checkActiveMenu('master/user*') }}">
            <a href="{{ route('backend.master.user') }}">User</a>
        </li>
        <li>
        <li>
            <a href="{{ route('backend.master.periodeaktif') }}">Periode Aktif</a>
        </li>
        <li>
            <a href="{{ route('backend.master.kompetensi') }}">Kompetensi</a>
        </li>
        </li>
        <li  class="{{ checkActiveMenu('master/jabatan*') }}">
            <a href="{{ route('backend.master.jabatan') }}">Jabatan</a>
        </li>
        <li class="{{ checkActiveMenu('master/user*') }}">
            <a href="{{ route('backend.master.user') }}">User</a>
        </li>
        <li>
            <a href="#">Hak Akses</a>
        </li>
        <li>
            <a href="{{ route('backend.master.periodeRealisasi') }}">Periode Realisasi</a>
        </li>
    </ul>
</div>