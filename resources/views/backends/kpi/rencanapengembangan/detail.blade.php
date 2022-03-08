@inject('gap','\Pkt\Domain\Realisasi\Services\GapValueCalculationService')
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 margin-top-30">
                <div class="panel panel-default panel-box panel-create">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box no-border">Rencana Pengembangan KPI <span class="label label-info pull-right">{{ strtoupper($data['header']->statusdokumen->StatusDokumen) }}</span></div>
                            @include('vendor.flash.message')
                        </div>
                        <div class="col-sm-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Data Karyawan</div>
                                <div class="panel-body">
                                    <ul class="list-group">
                                        <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['header']->NPK }}</span></li>
                                        <li class="list-group-item"><b>Nama Karyawan</b><span class="pull-right">{{ $data['header']->karyawan->NamaKaryawan }}</span> </li>
                                        <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{$data['header']->karyawan->organization->position->PositionTitle }}</span> </li>
                                        <li class="list-group-item"><b>Unit Kerja</b><span class="pull-right">{{$data['header']->karyawan->organization->position->unitkerja->Deskripsi }}</span> </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Data KPI</div>
                                <div class="panel-body">
                                    <ul class="list-group">
                                        <li class="list-group-item"><b>Tahun</b><span class="pull-right">{{ $data['header']->Tahun }}</span></li>
                                        <li class="list-group-item"><b>Periode</b><span class="pull-right">{{ $data['header']->jenisperiode->KodePeriode }}</span></li>
                                        <li class="list-group-item"><b>Nilai Akhir / Nilai Validasi</b><span class="pull-right">{{ $data['header']->NilaiAkhir>4?4.00:$data['header']->NilaiAkhir }} / {{ !empty($data['header']->NilaiValidasi)?$data['header']->NilaiValidasi>4?4.00:$data['header']->NilaiValidasi:'Belum tervalidasi' }}</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default panel-box panel-create">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box no-border no-margin-bottom">Detail Rencana Pengembangan KPI {{ $data['header']->Tahun . ' Periode: '. $data['header']->jenisperiode->KodePeriode }}</div>
                        </div>
                        <form action="{{ route('backends.kpi.rencanapengembangan.update', ['idrealisasiheader' => $data['header']->ID]) }}" method="post">
                            {!! csrf_field() !!}
                            <div class="margin-min-15" style="overflow-x: scroll; width: calc(100% + 30px);">
                                <table class="table table-striped" width="100%">
                                    <thead>
                                    <tr>
                                        <th class="col-sm-3">Judul Item KPI</th>
                                        <th>Persentase Realisasi</th>
                                        <th>Target</th>
                                        <th>Realisasi</th>
                                        <th>Gap</th>
                                        <th class="col-sm-2">Follow Up</th>
                                        <th class="col-sm-3">Rencana Pengembangan</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($data['alldetail'] as $item)
                                        @php
                                            $gap = \Pkt\Domain\Realisasi\Services\GapValueCalculationService::calculate($item->detilrencana->{'Target'.$data['header']->Target}, $item->Realisasi, $item->detilrencana->persentaserealisasi->KodePersentaseRealisasi);
                                        @endphp
                                        @if($gap >= 0)
                                            <input type="hidden" name="iddetailrealisasi[]" value="{{ $item->ID }}">
                                            <tr>
                                                <td>{{ $item->detilrencana->DeskripsiKPI }}</td>
                                                <td>{{ $item->detilrencana->persentaserealisasi->PersentaseRealisasi }}</td>
                                                <td>{{ numberDisplay($item->detilrencana->{'Target'.$data['header']->Target}) }}</td>
                                                <td>{{ numberDisplay($item->Realisasi) }}</td>
                                                <td>{{ $gap }}</td>
                                                <td>
                                                    <select name="IDRencanaPengembangan[]" class="form-control">
                                                        <option value="empty">Pilih Follow Up</option>
                                                        @foreach($data['followup'] as $followup)
                                                            <option value="{{ $followup->ID }}" {{ ($item->IDRencanaPengembangan == $followup->ID) ? 'selected' : '' }}>{{ strtoupper($followup->RencanaPengembangan) }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><textarea class="form-control" name="RencanaPengembangan[]" placeholder="Isi keterangan rencana pengembangan">{{ $item->RencanaPengembangan }}</textarea></td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="7">Tidak ada data.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right save-container">
                                <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                                <a href="{{ route('backends.kpi.rencanapengembangan') }}" class="btn btn-default btn-orange">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('customjs')
    <script>
        $(function() {
            $('#detailRencanaPengembangan-table').DataTable();
        });
    </script>
@endsection