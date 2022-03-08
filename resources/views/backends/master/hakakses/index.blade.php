@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.master')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <!--<div class="panel-title-box">Hak Akses User</div>-->
                            <div class="panel-title-box">***</div>
                        </div>
                        <!--<div class="col-sm-3 col-sm-offset-9">
                            <div class="custom-button-container text-right">
                                <button class="btn btn-link" id="datatable-user-refresh" title="Refresh data">
                                    <img src="{{ asset('assets/img/ic_refresh.png') }}">
                                </button>
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="user-table" class="table table-striped" width="100%">
                                <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>NPK</th>
                                    <th>Nama Karyawan</th>
                                    {{--@foreach($data['roles']as $role)--}}
                                        {{--<th>{{$role->Role}}</th>--}}
                                    {{--@endforeach--}}
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
@endsection

@section('customjs')
    <script>
        $(function() {
            $('#user-table').DataTable({
                processing: true,
                serverSide: true,
                // scrollX: true,
                ajax: '{!! route("backend.master.user.privilege") !!}',
                columns: [
                    { data: 'username', name: 'username' },
                    { data: 'NPK', name: 'Users.NPK'},
                    { data: 'karyawan.NamaKaryawan', name: 'karyawan.NamaKaryawan' },
//                    { data: 'user_role.Role', name: 'UserRole.Role' },
                    { data: 'Aksi', name: 'Aksi', sortable: false }
                ]
            });
        });

        $('#datatable-user-refresh').click(function() {
            $('#user-table').DataTable().ajax.reload();
        });

    </script>
@endsection