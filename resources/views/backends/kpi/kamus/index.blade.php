@extends('layouts.app')
@if(\auth()->user()->UserRole->Role === 'Administrator')
    @include('backends.kpi.kamus.document')
@endif
<style>
    th{
        min-width: 150px;
    }
</style>
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Data Kamus KPI</div>
                        </div>
                        <div class="col-sm-12 margin-top-15">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="alert alert-approved">
                                        <h4 class="title-alert">Approved</h4>
                                        <h2 class="content-alert">{{ $data['approved'] }}</h2>
                                        <div class="icon-alert">
                                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="alert alert-pending">
                                        <h4 class="title-alert">Pending</h4>
                                        <h2 class="content-alert">{{ $data['pending'] }}</h2>
                                        <div class="icon-alert">
                                            <i class="fa fa-hourglass-start" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="alert alert-rejected">
                                        <h4 class="title-alert">Rejected</h4>
                                        <h2 class="content-alert">{{ $data['rejected'] }}</h2>
                                        <div class="icon-alert">
                                            <i class="fa fa-times-circle" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="custom-button-container">
                                <a href="{{ route('backend.kpi.kamus.create') }}">
                                    <button class="btn btn-link">
                                        <img src="{{ asset('assets/img/ic_add.png') }}"> Tambah Data
                                    </button>
                                </a>
                                @if(\auth()->user()->UserRole->Role === 'Administrator')
                                    <a href="#">
                                        <button class="btn btn-link" onClick ="modalUpload()">
                                            <img src="{{ asset('assets/img/ic_edit.png') }}"> Import Excel
                                        </button>
                                    </a>
                                    <a href="{{ route('backend.kpi.kamus.excel') }}">
                                        <button class="btn btn-link">
                                            <img src="{{ asset('assets/img/ic_edit.png') }}"> Export Excel
                                        </button>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="custom-button-container text-right">
                                <button class="btn btn-link" id="datatable-kamuskpi-refresh" data-toggle="tooltip" data-placement="top" title="Refresh data">
                                    <img src="{{ asset('assets/img/ic_refresh.png') }}">
                                </button>
                            </div>
                        </div>
                        <div class="margin-min-15">
                            <table id="kamuskpi-table" class="table table-striped" style="width:1500px">
                                <thead>
                                <tr>
                                    <th rowspan="2" style="min-width: 100px;">Status</th>
                                    <th rowspan="2" style="min-width: 100px;">Aksi</th>
                                    <th rowspan="2">Kode Reg</th>
                                    <th rowspan="2" style="min-width: 250px;">Judul KPI</th>
                                    <th rowspan="2">Kode Unit Kerja</th>
                                    <th colspan="2" style="text-align: Center;">Indikator</th>
                                    <th rowspan="2" style="min-width: 250px;">Deskripsi</th>
                                    <th rowspan="2">Satuan</th>
                                    <th rowspan="2">Kelompok</th>
                                    <th rowspan="2">Persentase Realisasi</th>
                                    <th rowspan="2">Rumus</th>
                                    <th rowspan="2">Sumber Data</th>
                                    <th rowspan="2">Periode Laporan</th>
                                    <th rowspan="2">Jenis</th>
                                    <th rowspan="2">Sifat</th>
                                    <th rowspan="2">Catatan</th>
                                </tr>
                                <tr>
                                    <th>Hasil</th>
                                    <th>Kinerja</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-notification" id="kamusDeleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Hapus data kamus KPI?</h4>
                </div>
                <div class="modal-body">
                    Peringatan! Data yang dihapus tidak akan bisa dikembalikan.
                </div>
                <div class="modal-footer">
                    <button type="button" id="modal-confirm-delete-button" data-url="" class="btn btn-default btn-danger delete">Ya</button>
                    <button type="button" class="btn btn-default btn-no" data-dismiss="modal">Tidak</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        $(function() {
            $('#kamuskpi-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth :true,
                ajax:{
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'{!! route("backend.kpi.kamus.data") !!}',
                    method:'post'
                },
                order: [[0, 'desc']],
                columns: [
                    { data: 'Status', status: 'Status' },
                    { data: 'Aksi', orderable: false },
                    { data: 'KodeRegistrasi', name: 'KodeRegistrasi' },
                    { data: 'KPI', name: 'KPI' },
                    { data: 'KodeUnitKerja', name: 'KodeUnitKerja'},
                    { data: 'IndikatorHasil', name:'IndikatorHasil'},
                    { data: 'IndikatorKinerja', name:'IndikatorKinerja'},
                    { data: 'Deskripsi', name:'Deskripsi'},
                    { data: 'satuan.Satuan', name: 'satuan.Satuan'},
                    { data: 'aspekkpi.AspekKPI', name: 'aspekkpi.AspekKPI'},
                    { data: 'persentaserealisasi.PersentaseRealisasi', name: 'persentaserealisasi.PersentaseRealisasi'},
                    { data: 'Rumus', name: 'Rumus'},
                    { data: 'SumberData', name: 'SumberData'},
                    { data: 'PeriodeLaporan', name: 'PeriodeLaporan'},
                    { data: 'Jenis', name: 'Jenis'},
                    { data: 'jenisappraisal.JenisAppraisal', name: 'jenisappraisal.JenisAppraisal'},
                    { data: 'Keterangan', name: 'Keterangan'}
                ]
            });
        });

        $('#datatable-kamuskpi-refresh').click(function() {
            $('#kamuskpi-table').DataTable().ajax.reload();
        });

        $('#modal-confirm-delete-button').click(function()
        {
            $(this).attr('disabled','disabled');
            var url = $(this).data('url');
            var token = '{{ csrf_token() }}';
            var data = {_method: 'delete', _token: token};
            $.ajax({
                url: url,
                type: 'post',
                data: data,
                success: function(result) {
                    document.location.reload(true);
                },
                error: function(xhr) {
                    alert('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText);
                }
            });
        });
    </script>
@endsection