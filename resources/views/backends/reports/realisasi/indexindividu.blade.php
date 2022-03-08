@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <div class="panel panel-default panel-box">
                    <div class="col-sm-12">
                        <div class="panel-title-box">Laporan Realisasi KPI Individu Tahun <span class="badge">{{ $data['periode'] }}</span> Periode <span class="badge">{{ $data['parsePeriode']->KodePeriode }}</span></div>
                    </div>
                    <div class="filter-kpi">
                        <div class="col-sm-12">
                            <div class="row">
                                <form id="reportrealisasiindividu-form" action="{{ route('report.realisasi.kpiindividu.index') }}" method="get">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>NPK</label>
                                            <input type="text" name="npk" placeholder="NPK Karyawan" class="form-control" value="{{ request('npk') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Tahun Rencana</label>
                                            <select id="Tahun" name="tahunperiode" class="form-control" onchange="tahun()">
                                                @foreach($data['periodeAktif'] as $periode)
                                                    <option value="{{ $periode->Tahun }}" {{ (request('tahunperiode') == $periode->Tahun) ? 'selected' : '' }}>{{ $periode->Tahun }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Periode Realisasi</label>
                                            <select id="IDJenisPeriode" name="perioderealisasi" class="form-control">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status" class="form-control">
                                                <option value="all" {{ (request('status') == 'all' || request('status') == '' || ! request()->has('status')) ? 'selected' : '' }}>Pilih Status</option>
                                                <option value="inprogress" {{ (request('status') == 'inprogress') ? 'selected' : '' }}>Dalam Proses</option>
                                                <option value="approved" {{ (request('status') == 'approved') ? 'selected' : '' }}>Diterima</option>
                                                <option value="empty" {{ (request('status') == 'empty') ? 'selected' : '' }}>Belum Membuat</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <button class="btn btn-primary" type="submit">Cari</button>
                                    </div>
                                    <div class="col-sm-1">
                                        <a class="btn btn-yellow" href="{{ route('report.realisasi.kpiindividu.index') }}">Reset</a>
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-orange" type="submit" name="unduh"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="alert alert-info" role="alert">Total Karyawan <span class="badge pull-right">{{ $data['karyawanCount'] }}</span></div>
                    </div>
                    <div class="col-sm-3">
                        <div class="alert alert-success" role="alert">Realisasi Diterima <span class="badge pull-right">{{ $data['approvedCount'] }}</span></div>
                    </div>
                    <div class="col-sm-3">
                        <div class="alert alert-warning" role="alert">Dalam Proses <span class="badge pull-right">{{ $data['inprogressCount'] }}</span></div>
                    </div>
                    <div class="col-sm-3">
                        <div class="alert alert-danger" role="alert">Belum Membuat Realisasi<span class="badge pull-right">{{ $data['karyawanCount'] - ($data['approvedCount'] + $data['inprogressCount']) }}</span></div>
                    </div>
                    <div class="panel-body">
                        <div class="margin-min-15" style="overflow-x: scroll; width: calc(100% + 30px);">
                            <table id="reportrealisasiindividu-table" class="table table-striped" width="1500px">
                                <thead>
                                <tr>
                                    <th rowspan="2">No.</th>
                                    <th rowspan="2">NPK</th>
                                    <th rowspan="2" class="col-sm-2">Nama Lengkap</th>
                                    <th rowspan="2">Grade</th>
                                    <th rowspan="2" class="col-sm-w">Unit Kerja</th>
                                    <th rowspan="2" class="col-sm-w">Jabatan</th>
                                    <th colspan="3" class="text-center">Jumlah Item KPI</th>
                                    <th colspan="3" class="text-center">Nilai Awal</th>
                                    <th rowspan="2">Nilai Akhir</th>
                                    <th rowspan="2">Perubahan Nilai</th>
                                    <th rowspan="2">Status</th>
                                    <th rowspan="2" class="col-sm-2">Keterangan</th>
                                    <th rowspan="2">Created On</th>
                                </tr>
                                <tr>
                                    <th class="col-sm-1">SR + Rutin</th>
                                    <th>TF</th>
                                    <th>Total</th>
                                    <th class="col-sm-1">SR + Rutin</th>
                                    <th>TF</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($data['collection'] as $item)
                                        <tr {!! rowLabelReportRencanaIndividu($item->realisasikpi) !!}>
                                            <td>{{ $data['numbering']++ }}</td>
                                            <td>{{ $item->NPK }}</td>
                                            <td>{{ $item->NamaKaryawan }}</td>
                                            <td>{{ $item->organization->Grade }}</td>
                                            <td>{{ $item->organization->position->unitkerja->Deskripsi }}</td>
                                            <td>{{ $item->organization->position->PositionTitle }}</td>
                                            @php
                                                $rutinStrategi = 0;
                                                $taskForce = 0;
                                                $realisasiRutinStrategi = 0;
                                                $realisasiTaskForce = 0;
                                                $nilaiValidasi = 0;
                                                $totalRealisasi = 0;
                                                $perubahanNilai = false;
                                                if ($item->rencanakpi->count() > 0) {
                                                    $rutinStrategi = $item->rencanakpi[0]->detail()->nonTaskForce()->count();
                                                    $taskForce = $item->rencanakpi[0]->detail()->taskForce()->count();
                                                }
                                                if ($item->realisasikpi->count() > 0) {
                                                    $realisasiRutinStrategi = $item->realisasikpi[0]->NilaiAkhirNonTaskForce;
                                                    $realisasiTaskForce = $item->realisasikpi[0]->NilaiAkhir - $item->realisasikpi[0]->NilaiAkhirNonTaskForce;
                                                    $totalRealisasi = $item->realisasikpi[0]->NilaiAkhir;
                                                    $nilaiValidasi = ($item->realisasikpi[0]->NilaiValidasiNonTaskForce === null) ? $item->realisasikpi[0]->NilaiAkhir : 0.00;
                                                }
                                                $total = $rutinStrategi + $taskForce;
                                                $perubahanNilai = ($nilaiValidasi != $totalRealisasi);
                                            @endphp
                                            <td class="text-center">{{ $rutinStrategi }}</td>
                                            <td class="text-center">{{ $taskForce }}</td>
                                            <td class="text-center">{{ $total }}</td>
                                            <td class="text-center">{{ number_format($realisasiRutinStrategi, 2) }}</td>
                                            <td class="text-center">{{ number_format($realisasiTaskForce, 2) }}</td>
                                            <td class="text-center">{{ number_format($totalRealisasi, 2) }}</td>
                                            <td class="text-center">{{ number_format($nilaiValidasi, 2) }}</td>
                                            <td class="text-center">{{ ($item->rencanakpi->count() > 0) ? ($perubahanNilai ? 'BERUBAH' : 'TETAP') : '-' }}</td>
                                            <td>{{ ($item->realisasikpi->count() > 0) ? $item->realisasikpi[0]->statusdokumen->StatusDokumen : '-' }}</td>
                                            <td>{{ ($item->realisasikpi->count() > 0) ? $item->realisasikpi[0]->Keterangan : '-' }}</td>
                                            <td>{{ ($item->realisasikpi->count() > 0) ? $item->realisasikpi[0]->CreatedOn : '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="17" class="text-center"> Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            {!! $data['collection']->appends(request()->except('page'))->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        $('#reportrealisasiindividu-table').DataTable({
            'pageLength': 25,
            sDom: 't',
            columnDefs: [{targets: [0], orderable: false}],
            order: []
        });
        function tahun(){
            var tahun = $('#Tahun').val();
            var idjenisperiode = $('#IDJenisPeriode');
            var perioderealisasi = "{{request('perioderealisasi')}}";
            $.ajax({
                url: "{{ route('backends.kpi.realisasi.individu.periode',':tahun') }}".replace(':tahun',tahun),
                dataType: 'json',
                success: function(data){
                    idjenisperiode.find('option').remove().end();
                    for (var i = 0; i < data.length; i++){
                        var selected_ = '';
                        if(perioderealisasi == data[i].IDJenisPeriode){selected_ = 'selected'}
                        idjenisperiode.append(''+
                            '<option value="' + data[i].IDJenisPeriode + '"'+ selected_+'>'+ data[i].jenisperiode.NamaPeriodeKPI+'</option>'
                        );
                    }
                }
            });
        }
        $('#reportrealisasiindividu-form').ready(function () {
            tahun();
        });
    </script>
@endsection