@extends('layouts.app')
<style>
    h1 a{
        color: black;
    }
</style>
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12">
             <section class="landing-home-parallax" style="min-height: 50%"></section>
        </div>
        <div class="col-md-8 col-sm-12">
            <div class="col-sm-12 hidden-xs" style="margin-top: -10%;">
                <h1><a href="{{route('profile')}}">{{$data['karyawan']->NamaKaryawan}}</a></h1>
            </div>
            <div class="col-sm-12" style="margin-top: 3%;">
                <div class="panel panel-default panel-box panel-create">
                    <div class="panel-body">
                        @include('vendor.flash.message')
                        <div class="panel panel-primary">
                            <div class="panel-heading">Profil</div>
                            <div class="panel-body">
                                <ul class="list-group">
                                    <li class="list-group-item"><b>NPK</b><span
                                                class="pull-right">{{ $data['karyawan']->NPK }}</span></li>
                                    <li class="list-group-item"><b>Username</b><span
                                                class="pull-right">{{ $data['karyawan']->user->username }}</span></li>
                                    <li class="list-group-item"><b>Email</b><span
                                                class="pull-right">{{ $data['karyawan']->Email or '-' }}</span></li>
                                    <li class="list-group-item"><b>Grade</b><span
                                                class="pull-right">{{ $data['organization']->Grade }}</span></li>
                                    <li class="list-group-item"><b>Kode Jabatan</b><span
                                                class="pull-right">{{ $data['position']->PositionID }}</span></li>
                                    <li class="list-group-item"><b>Jabatan</b><span
                                                class="pull-right">{{ $data['position']->PositionTitle }}</span></li>
                                    <li class="list-group-item"><b>Position Abbreviation</b><span
                                                class="pull-right">{{ $data['position']->PositionAbbreviation }}</span>
                                    </li>
                                    <li class="list-group-item"><b>Kode Unit Kerja</b><span
                                                class="pull-right">{{ $data['position']->KodeUnitKerja }}</span></li>
                                    <li class="list-group-item"><b>Unit Kerja</b><span
                                                class="pull-right">{{ $data['unitkerja']->Deskripsi }}</span></li>
                                    <li class="list-group-item"><b>Hak Akses</b><span
                                                class="pull-right">{{empty($data['role'])?'-':$data['role']}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="panel img-logo" style="height: 100%;">
                <div class="col-sm-12 visible-xs" style="margin-top: -10%;">
                    <h3><a href="{{route('profile')}}">{{$data['karyawan']->NamaKaryawan}}</a></h3>
                </div>
                <hr>
                <div class="panel-body">
                    <div class="panel-heading visible-xs"><h3><b>Pengaturan</b></h3></div>
                    <div class="panel-body">
                        <ul class="list-group">
                            <a class=" btn btn-blue btn-full center" href="{{route('profile.edit')}}">Ubah Password</a>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
