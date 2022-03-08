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
                            <div class="panel-title-box">Penugasan</div>
                        </div>
                        <div class="col-sm-3 col-sm-offset-9">
                            <div class="custom-button-container text-right">
                                <button class="btn btn-link" title="Refresh data" id="datatable-organizationalAssignment-refresh">
                                    <img src="{{ asset('assets/img/ic_refresh.png') }}">
                                </button>
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="organizationalAssignment-table" class="table table-striped" width="100%">
                                <thead>
                                <tr>
                                    <th>NPK</th>
                                    <th>Nama Karyawan</th>
                                    <th>Grade</th>
                                    <th>Position Abbreviation</th>
                                    <th>Position Title</th>
                                    <th>Kode Unit Kerja</th>
                                    <th>Unit Kerja</th>
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
            $('#organizationalAssignment-table').DataTable({
                processing: true,
                serverSide: true,
                // scrollX: true,
                ajax: '{!! route("backend.master.organizationalAssignment") !!}',
                columns: [
                    { data: 'NPK', name: 'NPK' },
                    { data: 'karyawan.NamaKaryawan', name: 'karyawan.NamaKaryawan' },
                    { data: 'Grade', name: 'Grade'},
                    { data: 'position.PositionAbbreviation', name: 'position.PositionAbbreviation' },
                    { data: 'position.PositionTitle', name: 'position.PositionTitle' },
                    { data: 'position.unitkerja.CostCenter', name: 'position.unitkerja.CostCenter' },
                    { data: 'position.unitkerja.Deskripsi', name: 'position.unitkerja.Deskripsi' }
                ]
            });
        });

        $('#datatable-organizationalAssignment-refresh').click(function() {
            $('#organizationalAssignment-table').DataTable().ajax.reload();
        });
    </script>
@endsection