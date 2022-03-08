@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.realisasi')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 margin-top-15">
                <ul class="kpi-menu">
                    <li>
                        <a href="{{ route('backends.kpi.realisasi.individu.detailbawahanlangsung', ['id' => $data['headerrealisasi']->ID]) }}">Realisasi KPI</a>
                    </li>
                    <li class="active">
                        <a href="{{ route('backends.kpi.realisasi.individu.documentbawahanlangsung', ['id' => $data['headerrealisasi']->ID]) }}">Dokumen</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 margin-top-30">
                <div class="panel panel-default panel-box panel-create">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="border-bottom-container margin-bottom-15">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">Data Karyawan</div>
                                    <div class="panel-body">
                                        <ul class="list-group">
                                            <li class="list-group-item"><strong>Tahun</strong><span class="pull-right">{{ $data['headerrealisasi']->Tahun }}</span></li>
                                            <li class="list-group-item"><strong>NPK</strong><span class="pull-right">{{ $data['karyawan']->NPK }}</span></li>
                                            <li class="list-group-item"><strong>Nama Karyawan</strong><span class="pull-right">{{ $data['karyawan']->NamaKaryawan }}</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="panel-title-box no-border no-margin-bottom">Daftar Dokumen Realisasi KPI</div>
                        </div>
                        <div class="margin-min-15">
                            <table class="table table-striped" width="100%">
                                <thead>
                                <tr>
                                    <th>Caption</th>
                                    <th>Keterangan</th>
                                    <th>Extension</th>
                                    <th>Created On</th>
                                    <th class="text-center">Berkas</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($data['headerrealisasi']->attachments as $attachment)
                                    <tr>
                                        <td>{{ $attachment->Caption }}</td>
                                        <td>{{ $attachment->Keterangan }}</td>
                                        <td>{{ $attachment->ContentType }}</td>
                                        <td>{{ $attachment->CreatedOn }}</td>
                                        <td class="text-center"><a href="{{ route('backends.kpi.realisasi.individu.documentdownload', ['id' => $data['headerrealisasi']->ID, 'attachmentID' => $attachment->ID]) }}" class="btn btn-info btn-sm"><i class="fa fa-download"></i> Unduh</a> </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada dokumen</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
