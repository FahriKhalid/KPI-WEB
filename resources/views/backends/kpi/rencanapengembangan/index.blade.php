@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.rencana')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Rencana Pengembangan</div>
                        </div>
                        <div class="col-sm-offset-9 col-sm-3">
                            <div class="custom-button-container text-right">
                                <button class="btn btn-link" id="datatable-rencanapengembangan-refresh">
                                    <img src="{{ asset('assets/img/ic_refresh.png') }}">
                                </button>
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="rencanapengembangan-table" class="table table-striped" style="width:1500px;">
                                <thead>
                                <tr>
                                    <th>Aksi</th>
                                    <th>NPK</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Unit Kerja</th>
                                    <th>Tahun</th>
                                    <th>Periode</th>
                                    <th>Rencana Pengembangan</th>
                                    <th>Nilai KPI</th>
                                    <th>Nilai Validasi</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-notification" id="actionConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close noCallButton" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('assets/img/ic_warning.png') }}" class="img-warning-modal">
                    <h4 class="modal-title" id="actionModalTitle"></h4>
                    <p id="actionModalContent"></p>
                    <form id="modalform" class="form-horizontal">
                        {!! csrf_field() !!}
                        <div class="form-group" id="formgroup-alasan" style="display: none;">
                            <label class="col-sm-12 control-label text-left">Catatan Unapprove</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="CatatanUnapprove" rows="2" id="inputAlasanUnapprove" placeholder="Berikan catatan mengapa dokumen di unapprove"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-yellow" id="callButton">Ya</button>
                    <button type="button" class="btn btn-default btn-no noCallButton"  data-dismiss="modal">Tidak</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        $('#rencanapengembangan-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route("backends.kpi.rencanapengembangan") !!}',
            order: [[1, 'desc']],
            columns: [
                {data: 'Aksi', name: 'Aksi', orderable: false},
                {data: 'NPK', name: 'NPK'},
                {data: 'karyawan.NamaKaryawan', name: 'karyawan.NamaKaryawan'},
                {data: 'masterposition.PositionTitle', name: 'masterposition.PositionTitle'},
                {data: 'masterposition.unitkerja.Deskripsi', name: 'masterposition.unitkerja.Deskripsi'},
                {data: 'Tahun', name: 'Tahun'},
                {data: 'jenisperiode.KodePeriode', name: 'jenisperiode.KodePeriode'},
                {data: 'CountRencanaPengembangan', defaultContent: '-', searchable: false},
                {data: 'NilaiAkhir', name: 'NilaiAkhir', defaultContent: '-' },
                {data: 'NilaiValidasi', name: 'NilaiValidasi', defaultContent: '-'}
            ]
        });
        $('#datatable-rencanapengembangan-refresh').click(function() {
            $('#rencanapengembangan-table').DataTable().ajax.reload();
        });
    </script>
@endsection