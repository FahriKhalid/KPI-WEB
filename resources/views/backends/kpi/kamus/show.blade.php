@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Detail Data Kamus KPI</div>
                            <div class="row">
                                <div class="col-sm-8">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>Kode Registrasi</strong> <span class="pull-right">{{ (! empty($data->KodeRegistrasi)) ? $data->KodeRegistrasi : '-' }}</span></li>
                                        <li class="list-group-item"><strong>Kode Unit Kerja</strong> <span class="pull-right">{{ $data->KodeUnitKerja }}</span></li>
                                        <li class="list-group-item"><strong>Aspek KPI</strong> <span class="pull-right">{{ $data->aspekkpi->AspekKPI }}</span></li>
                                        <li class="list-group-item"><strong>Jenis Appraisal</strong> <span class="pull-right">{{ $data->jenisappraisal->JenisAppraisal }}</span></li>
                                        <li class="list-group-item"><strong>Persentase Realisasi</strong> <span class="pull-right">{{ $data->persentaserealisasi->PersentaseRealisasi }}</span></li>
                                        <li class="list-group-item"><strong>Satuan</strong> <span class="pull-right">{{ $data->satuan->Satuan }}</span></li>
                                        <li class="list-group-item"><strong>Judul</strong> <span class="pull-right">{{ $data->KPI }}</span></li>
                                        <li class="list-group-item"><strong>Rumus</strong> <span class="pull-right">{{ (! empty($data->Rumus)) ? $data->Rumus : '-' }}</span></li>
                                        <li class="list-group-item"><strong>Periode Laporan</strong> <span class="pull-right">{{ (! empty($data->PeriodeLaporan)) ? $data->PeriodeLaporan : '-' }}</span></li>
                                        <li class="list-group-item"><strong>Keterangan</strong> <span class="pull-right">{{ (! empty($data->Keterangan)) ? $data->Keterangan : '-' }}</span></li>
                                        <li class="list-group-item"><strong>Dibuat Oleh</strong> <span class="pull-right">{{ $data->createdby->username }}</span></li>
                                        <li class="list-group-item"><strong>Dibuat Pada</strong> <span class="pull-right">{{ $data->CreatedOn }}</span></li>
                                        <li class="list-group-item"><strong>Diperbarui Oleh</strong> <span class="pull-right">{{ (! empty($data->updatedby->username)) ? $data->updatedby->username : '-' }}</span></li>
                                        <li class="list-group-item"><strong>Diperbarui Pada</strong> <span class="pull-right">{{ (! empty($data->UpdatedOn)) ? $data->UpdatedOn : '-' }}</span></li>
                                        <li class="list-group-item"><strong>Disetujui Oleh</strong> <span class="pull-right">{{ (! empty($data->approvedby->username)) ? $data->approvedby->username : '-' }}</span></li>
                                        <li class="list-group-item"><strong>Disetujui Pada</strong> <span class="pull-right">{{ (! empty($data->ApprovedOn)) ? $data->ApprovedOn : '-' }}</span></li>
                                    </ul>
                                </div>
                                <div class="col-sm-4">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>Deskripsi</strong></li>
                                        <li class="list-group-item">{!! $data->Deskripsi !!}</li>
                                    </ul>
                                </div>
                                <div class="col-sm-8">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <ul class="list-group">
                                                <li class="list-group-item"><strong>Indikator :</strong></li>
                                            </ul>
                                            <ul class="list-group">
                                                <li class="list-group-item"><strong>Hasil</strong> <span class="pull-right">{{ (! empty($data->IndikatorHasil)) ? $data->IndikatorHasil : '-' }}</span></li>
                                                <li class="list-group-item"><strong>Kinerja</strong> <span class="pull-right">{{ (! empty($data->IndikatorKinerja)) ? $data->IndikatorKinerja : '-' }}</span></li>
                                            </ul>
                                        </li>
                                        <li class="list-group-item"><strong>Sumber Data</strong> <span class="pull-right">{{ (! empty($data->SumberData)) ? $data->SumberData : '-' }}</span></li>
                                        <li class="list-group-item"><strong>Jenis</strong> <span class="pull-right">{{ (! empty($data->Jenis)) ? $data->Jenis : '-' }}</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="text-center">
                                    <a href="{{ route('backend.kpi.kamus') }}" type="button" class="btn btn-default btn-orange">Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
