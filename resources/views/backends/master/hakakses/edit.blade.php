@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.master')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <form id="edit-user-form" class="form-horizontal" method="post" action="{{ route('backend.master.user.privilege.update', ['id' => $data['user']->ID]) }}">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="put">
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="panel-title-box">Edit Hak Akses User</div>
                            </div>
                            <div class="col-sm-11 col-sm-offset-1">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">NPK</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="NPK" placeholder="NPK Karyawan" class="form-control" value="{{ $data['user']->NPK }}" required readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Username</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="username" class="form-control" placeholder="Username" value="{{ $data['user']->username }}" required readonly>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Hak Akses</label>
                                    <div class="col-sm-7">
                                        <ul class="container-check">
                                            @foreach($data['roles'] as $role)
                                                <li>
                                                    <label><input type="checkbox" name="IDRole[]" value="{{$role->ID}}" {{in_array($role->ID,$data['privilege'])?'checked':''}}> {{$role->Role}}</label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right save-container">
                        <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                        <a href="{{ route('backend.master.user.privilege') }}" type="button" class="btn btn-default btn-orange">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('customjs')

@endsection