@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.master')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Data Karyawan</div>
                        </div>
                        <div class="col-sm-3 col-sm-offset-9">
                            <div class="custom-button-container text-right">
                                <button class="btn btn-link" title="Refresh data" id="datatable-karyawan-refresh">
                                    <img src="{{ asset('assets/img/ic_refresh.png') }}">
                                </button>
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="karyawan-table" class="table table-striped" width="100%">
                                <thead>
                                <tr>
                                    <th>Aksi</th>
                                    <th>NPK</th>
                                    <th>Nama Karyawan</th>
                                    <th>Email</th>
                                    <th>Position Abbreviation</th>
                                    <th>Kode Unit Kerja</th>
                                    <th>Unit Kerja</th>
                                    <th>Posisi</th>
                                    <th>Grade</th>
                                    <th>Shift</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        $(function() {
            $('#karyawan-table').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 400,
                ajax: '{!! route("backend.master.karyawan") !!}',
                order: [],
                columns: [
                    { data: 'Aksi', orderable: false },
                    { data: 'NPK', name: 'Ms_Karyawan.NPK' },
                    { data: 'NamaKaryawan', name: 'Ms_Karyawan.NamaKaryawan' },
                    { data: 'Email', name: 'Ms_Karyawan.Email', defaultContent: '-', searchable: false },
                    { data: 'PositionAbbreviation', name: 'Ms_MasterPosition.PositionAbbreviation', defaultContent: '-' },
                    { data: 'CostCenter', name: 'Ms_UnitKerja.CostCenter', defaultContent: '-' },
                    { data: 'Deskripsi', name: 'Ms_UnitKerja.Deskripsi', defaultContent: '-' },
                    { data: 'PositionTitle', name: 'Ms_MasterPosition.PositionTitle', defaultContent: '-' },
                    { data: 'Grade', name: 'View_OrganizationalAssignment.Grade', defaultContent: '-' },
                    { data: 'Shift', name: 'View_OrganizationalAssignment.Shift', defaultContent: '-' }
                ]
            });
        });

        $('#datatable-karyawan-refresh').click(function() {
            $('#karyawan-table').DataTable().ajax.reload();
        });
    </script>
@endsection