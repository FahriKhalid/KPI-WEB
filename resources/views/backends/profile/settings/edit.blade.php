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
                <div class="col-sm-12" style="margin-top: 10%;">
                    <form id="editprofileform" class="form-horizontal" method="post" action="{{route('profile.update') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_method" value="put">
                        <input type="hidden" name="personal" value="personal">
                        <div class="panel panel-default panel-box panel-create">
                            <div class="panel-body">
                                <div class="col-sm-12">
                                     <div class="panel-title-box">Ubah Password</div>
                                     @include('vendor.flash.message')
                                </div>
                                <div class="col-sm-11 col-sm-offset-1 margin-top-30">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Password Lama</label>
                                        <div class="col-sm-8">
                                            <input type="password" name="old_password" class="form-control" placeholder="Password Lama" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Password Baru</label>
                                        <div class="col-sm-8">
                                            <input type="password" name="password" class="form-control" placeholder="Password Baru" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Konfirmasi Password Baru</label>
                                        <div class="col-sm-8">
                                            <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password Baru" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12 text-right save-container">
                                            <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                                            <a href="{{ route('profile') }}" class="btn btn-default btn-orange">Batal</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection