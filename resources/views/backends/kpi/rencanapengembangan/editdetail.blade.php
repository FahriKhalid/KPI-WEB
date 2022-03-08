@inject('gap','\Pkt\Domain\Realisasi\Services\GapValueCalculationService')
@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.realisasi')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 margin-top-30">
                <div class="panel panel-default panel-box panel-create">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box no-border">Rencana Pengembangan KPI <span class="label label-info pull-right">{{ strtoupper($data['header']->statusdokumen->StatusDokumen) }}</span></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Data Karyawan</div>
                                <div class="panel-body">
                                    <ul class="list-group">
                                        <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['header']->NPK }}</span></li>
                                        <li class="list-group-item"><b>Nama Karyawan</b><span class="pull-right">{{ $data['karyawan']->NamaKaryawan }}</span> </li>
                                        <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ $data['karyawan']->organization->position->PositionTitle }}</span> </li>
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
                                        <li class="list-group-item"><b>Periode</b><span class="pull-right">{{ $data['header']->periodeaktif->jenisperiode->KodePeriode }}</span></li>
                                        <li class="list-group-item"><b>Nilai Akhir / Nilai Validasi</b><span class="pull-right">{{ $data['header']->NilaiAkhir }} / {{ !empty($data['header']->NilaiValidasi)?$data['header']->NilaiValidasi:'Belum tervalidasi' }}</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default panel-box panel-create">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box no-border no-margin-bottom">Detail Rencana Pengembangan KPI {{ $data['periode']->NamaPeriode }}</div>
                        </div>
                        <form class="form-horizontal" method="post" action="{{ route('backends.kpi.rencanapengembangan.storedetail', ['id' => $data['header']->ID]) }}">
                            {!! csrf_field() !!}
                        <div class="margin-min-15" style="overflow-x: auto;">
                            <div class="table-responsive">
                            <table class="table table-striped" width="100%">
                                <thead>
                                <tr>
                                    <th width="30%">Judul Item KPI</th>
                                    <th width="5%">Target {{ $data['periode']->jenisperiode->KodePeriode }}</th>
                                    <th width="5%">Total Nilai Target</th>
                                    <th width="5%">Nilai Akhir (%)</th>
                                    <th width="5%">Gap</th>
                                    <th width="15%">Follow Up</th>
                                    <th width="18.5%">Rencana Pengembangan</th>
                                    <th width="18.5%">Keterangan</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $totalBobot = 0;
                                    $strategis = 0.00;
                                    $rutin = 0.00;
                                    $taskforce = 0.00;
                                    $totalKPI = 0.00;
                                    $arrTotalKPI = [];
                                @endphp
                                @forelse($data['alldetail'] as $detail)
                                    @php
                                        $persentase = \Pkt\Domain\Realisasi\Services\TargetValueCalculationService::calculate($detail->detilrencana->{'Target'.$data['targetRealization']}, $detail->Realisasi);
                                        $convertion = \Pkt\Domain\Realisasi\Services\ConvertionService::convert($persentase);
                                        $final = \Pkt\Domain\Realisasi\Services\FinalValueService::calculate($convertion, $detail->detilrencana->Bobot);
                                        $arrTotalKPI[] = $final;
                                    @endphp
                                    @if($detail->detilrencana->aspekkpi->AspekKPI == 'Strategis')
                                        @php
                                            $strategis += $final;
                                        @endphp
                                    @elseif($detail->detilrencana->aspekkpi->AspekKPI == 'Rutin Struktural' || $detail->detilrencana->aspekkpi->AspekKPI == 'Rutin Operasional')
                                        @php
                                            $rutin += $final;
                                        @endphp
                                    @else
                                        @php
                                            $taskforce += $final;
                                        @endphp
                                    @endif
                                    <tr {!! rowLabelRealization($convertion) !!}>
                                        <input type="hidden" name="idrealisasiitem[]" value="{{ $detail->ID }}">
                                        <td>{{ $detail->detilrencana->DeskripsiKPI }}</td>
                                        <td>{{ $detail->detilrencana->{'Target'.$data['targetRealization']} }}</td>
                                        <td>{{ number_format($persentase, 2) }}</td>
                                        <td>{{ number_format($final/4*100,2)}}</td>
                                        <td>{{ $gap::calculate($detail->detilrencana->{'Target'.$data['targetRealization']}, $detail->Realisasi) }}</td>
                                        <td>{!! Form::select('FollowUp[]', ['' => '-- Pilih Follow Up --'] + $data['followup'], isset($detail->detilrencana->pengembangan->RencanaPengembangan)?$detail->detilrencana->pengembangan->RencanaPengembangan:old('FollowUp'), ['class' => 'form-control']) !!}</td>{{--+['required']--}}
                                        <td><input class="form-control" type="text" name="RencanaPengembangan[]" value="{{ isset($detail->detilrencana->RencanaPengembangan)?$detail->detilrencana->RencanaPengembangan:old('RencanaPengembangan') }}"></td>
                                        <td><input class="form-control" type="text" name="Keterangan[]" value="{{ isset($detail->detilrencana->pengembangan->Keterangan)?$detail->detilrencana->pengembangan->Keterangan:old('Keterangan') }}"></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data detail rencana KPI</td>
                                    </tr>
                                @endforelse
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th>{{ number_format(array_sum($arrTotalKPI)/4*100, 2) }}</th>
                                    <th>{{ $gap::calculatetotal($data['header']->ID) }}</th>

                                </tr>
                                <tr>
                                    <th colspan="3">Total Nilai KPI</th>
                                    <th>{{ number_format(array_sum($arrTotalKPI), 2) }}</th>
                                </tr>
                                </tfoot>
                            </table>
                            </div>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('backends.kpi.rencanapengembangan') }}" class="btn btn-default">Kembali</a>
                            @if(auth()->user()->UserRole->Role === 'Karyawan')
                                <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                            @endif
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
