@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.rencana')
@endsection

@section('content') 
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 margin-top-30">
                <div class="panel panel-default panel-box panel-create">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box no-border">Detail Karyawan</div>
                        </div>
                        <div class="col-sm-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Data Karyawan</div>
                                <div class="panel-body">
                                    <ul class="list-group">
                                        <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['karyawan']->NPK }}</span></li>
                                        <li class="list-group-item"><b>Nama Karyawan</b><span class="pull-right">{{ $data['karyawan']->NamaKaryawan }}</span> </li>
                                        <li class="list-group-item"><b>Grade</b><span class="pull-right">{{ $data['karyawan']->organization->Grade }}</span> </li>
                                        <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ $data['karyawan']->organization->position->PositionTitle }}</span> </li>
                                        <li class="list-group-item"><b>Kode Unit Kerja</b><span class="pull-right">{{ $data['karyawan']->organization->position->KodeUnitKerja }}</span></li>
                                        <li class="list-group-item"><b>Unit Kerja</b><span class="pull-right">{{ $data['karyawan']->organization->position->unitkerja->Deskripsi }}</span> </li>
                                        <li class="list-group-item"><b>Position Abbreviation</b><span class="pull-right">{{ $data['karyawan']->organization->position->PositionAbbreviation }}</span> </li>
                                        <li class="list-group-item"><b>Shift</b><span class="pull-right">{{ $data['karyawan']->organization->Shift }}</span> </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Atasan Langsung</div>
                                <div class="panel-body">
                                    <ul class="list-group">

                                        @if($data["atasanLangsung"] !== null && $data["atasanLangsung"]->Grade == "0D")
                                        <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ ($data['atasanLangsung'] !== null) ? $data['atasanLangsung']->Npk : '-'}}</span></li>
                                        <li class="list-group-item"><b>Nama Karyawan</b><span class="pull-right">{{ ($data['atasanLangsung'] !== null) ? $data['atasanLangsung']->Nama : '-' }}</span></li>
                                        <li class="list-group-item"><b>Grade</b><span class="pull-right">{{ ($data['atasanLangsung'] !== null) ? $data['atasanLangsung']->Grade : '-' }}</span> </li>
                                        <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ ($data['atasanLangsung'] !== null) ? $data['atasanLangsung']->Jabatan : '-' }}</span> </li>
                                        <li class="list-group-item"><b>Kode Unit Kerja</b><span class="pull-right">{{ ($data['atasanLangsung'] !== null) ? $data['atasanLangsung']->KodeUnitKerja : '-' }}</span></li>
                                        <li class="list-group-item"><b>Unit Kerja</b><span class="pull-right">{{ ($data['atasanLangsung'] !== null) ? $data['atasanLangsung']->Jabatan : '-' }}</span> </li>
                                        <li class="list-group-item"><b>Position Abbreviation</b><span class="pull-right">{{ ($data['atasanLangsung'] !== null) ? $data['atasanLangsung']->PositionAbbreviation : '-' }}</span> </li>
                                        <li class="list-group-item"><b>Shift</b><span class="pull-right">{{ ($data['atasanLangsung'] !== null) ? $data['atasanLangsung']->Shift : '-' }}</span> </li>
                                        @else
                                        <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ ($data['atasanLangsung'] !== null) ? $data['atasanLangsung']->NPK : '-'}}</span></li>
                                        <li class="list-group-item"><b>Nama Karyawan</b><span class="pull-right">{{ ($data['atasanLangsung'] !== null) ? $data['atasanLangsung']->NamaKaryawan : '-' }}</span></li>
                                        <li class="list-group-item"><b>Grade</b><span class="pull-right">{{ ($data['atasanLangsung'] !== null) ? $data['atasanLangsung']->organization->Grade : '-' }}</span> </li>
                                        <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ ($data['atasanLangsung'] !== null) ? $data['atasanLangsung']->organization->position->PositionTitle : '-' }}</span> </li>
                                        <li class="list-group-item"><b>Kode Unit Kerja</b><span class="pull-right">{{ ($data['atasanLangsung'] !== null) ? $data['atasanLangsung']->organization->position->KodeUnitKerja : '-' }}</span></li>
                                        <li class="list-group-item"><b>Unit Kerja</b><span class="pull-right">{{ ($data['atasanLangsung'] !== null) ? $data['atasanLangsung']->organization->position->unitkerja->Deskripsi : '-' }}</span> </li>
                                        <li class="list-group-item"><b>Position Abbreviation</b><span class="pull-right">{{ ($data['atasanLangsung'] !== null) ? $data['atasanLangsung']->organization->position->PositionAbbreviation : '-' }}</span> </li>
                                        <li class="list-group-item"><b>Shift</b><span class="pull-right">{{ ($data['atasanLangsung'] !== null) ? $data['atasanLangsung']->organization->Shift : '-' }}</span> </li>
                                        @endif 
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Atasan Tak Langsung</div>
                                <div class="panel-body">
                                    <ul class="list-group">
                                        @if($data["atasanTakLangsung"] !== null && $data["atasanTakLangsung"]->Grade == "0D")
                                        <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ ($data['atasanTakLangsung'] !== null) ? $data['atasanTakLangsung']->Npk : '-'}}</span></li>
                                        <li class="list-group-item"><b>Nama Karyawan</b><span class="pull-right">{{ ($data['atasanTakLangsung'] !== null) ? $data['atasanTakLangsung']->Nama : '-' }}</span></li>
                                        <li class="list-group-item"><b>Grade</b><span class="pull-right">{{ ($data['atasanTakLangsung'] !== null) ? $data['atasanTakLangsung']->Grade : '-' }}</span> </li>
                                        <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ ($data['atasanTakLangsung'] !== null) ? $data['atasanTakLangsung']->Jabatan : '-' }}</span> </li>
                                        <li class="list-group-item"><b>Kode Unit Kerja</b><span class="pull-right">{{ ($data['atasanTakLangsung'] !== null) ? $data['atasanTakLangsung']->KodeUnitKerja : '-' }}</span></li>
                                        <li class="list-group-item"><b>Unit Kerja</b><span class="pull-right">{{ ($data['atasanTakLangsung'] !== null) ? $data['atasanTakLangsung']->Jabatan : '-' }}</span> </li>
                                        <li class="list-group-item"><b>Position Abbreviation</b><span class="pull-right">{{ ($data['atasanTakLangsung'] !== null) ? $data['atasanTakLangsung']->PositionAbbreviation : '-' }}</span> </li>
                                        <li class="list-group-item"><b>Shift</b><span class="pull-right">{{ ($data['atasanTakLangsung'] !== null) ? $data['atasanTakLangsung']->Shift : '-' }}</span> </li>
                                        @else
                                        <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ ($data['atasanTakLangsung'] !== null) ? $data['atasanTakLangsung']->NPK : '-' }}</span></li>
                                        <li class="list-group-item"><b>Nama</b><span class="pull-right">{{ ($data['atasanTakLangsung'] !== null) ? $data['atasanTakLangsung']->NamaKaryawan : '-' }}</span></li>
                                        <li class="list-group-item"><b>Grade</b><span class="pull-right">{{ ($data['atasanTakLangsung'] !== null) ? $data['atasanTakLangsung']->organization->Grade : '-' }}</span> </li>
                                        <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ ($data['atasanTakLangsung'] !== null) ? $data['atasanTakLangsung']->organization->position->PositionTitle : '-' }}</span> </li>
                                        <li class="list-group-item"><b>Kode Unit Kerja</b><span class="pull-right">{{ ($data['atasanTakLangsung'] !== null) ? $data['atasanTakLangsung']->organization->position->KodeUnitKerja : '-' }}</span></li>
                                        <li class="list-group-item"><b>Unit Kerja</b><span class="pull-right">{{ ($data['atasanTakLangsung'] !== null) ? $data['atasanTakLangsung']->organization->position->unitkerja->Deskripsi : '-' }}</span> </li>
                                        <li class="list-group-item"><b>Position Abbreviation</b><span class="pull-right">{{ ($data['atasanTakLangsung'] !== null) ? $data['atasanTakLangsung']->organization->position->PositionAbbreviation : '-' }}</span> </li>
                                        <li class="list-group-item"><b>Shift</b><span class="pull-right">{{ ($data['atasanTakLangsung'] !== null) ? $data['atasanTakLangsung']->organization->Shift : '-' }}</span> </li>
                                        @endif
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center"><a href="{{ route('backend.master.karyawan') }}" class="btn btn-default">Kembali</a> </div>
                </div>
            </div>
        </div>
    </div>
@endsection