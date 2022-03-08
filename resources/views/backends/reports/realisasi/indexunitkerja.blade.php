@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <div class="panel panel-default panel-box">
                    <div class="col-sm-12">
                        <div class="panel-title-box">Laporan Realisasi KPI Individu Per Unit Kerja Tahun <span class="badge">{{ $data['periode'] }}</span> Periode <span class="badge">{{ $data['parsePeriode']->KodePeriode }}</span></div>
                    </div>
                    <div class="filter-kpi">
                        <div class="col-sm-12">
                            <div class="row">
                                <form id="reportrealisasiunitkerja-form" action="{{ route('report.realisasi.kpiunitkerja.index') }}" method="get">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Unit Kerja</label>
                                            {!! Form::select('unitkerja', ['' => 'Pilih Unit Kerja'] + $data['unitkerja'], request('unitkerja'), ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Periode Rencana</label>
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
                                    <div class="col-sm-1">
                                        <button class="btn btn-primary" type="submit">Cari</button>
                                    </div>
                                    <div class="col-sm-1">
                                        <a class="btn btn-yellow" href="{{ route('report.realisasi.kpiunitkerja.index') }}">Reset</a>
                                    </div>
                                    <div class="col-sm-2 col-sm-offset-1">
                                        <button class="btn btn-orange" type="submit" name="unduh"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="alert alert-info" role="alert">Total Unit Kerja <span class="badge pull-right">{{ count($data['unitkerja']) }}</span></div>
                    </div>
                    <div class="col-sm-3">
                        <div class="alert alert-success" role="alert">Sudah Mengumpulkan <span class="badge pull-right">{{ $data['collectedCount'] }}</span></div>
                    </div>
                    <div class="col-sm-3">
                        <div class="alert alert-warning" role="alert">Belum Mengumpulkan<span class="badge pull-right">{{ $data['uncollectedCount'] }}</span></div>
                    </div>
                    <div class="col-sm-3">
                        <div class="alert alert-danger" role="alert">Total Progress<span class="badge pull-right">{{ $data['progressPercentage'] }}%</span></div>
                    </div>
                    <div class="panel-body">
                        <div class="margin-min-15" style="overflow-x: scroll; width: calc(100% + 30px);">
                            <table id="reportrealisasiunitkerja-table" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Unit Kerja</th>
                                    <th>Jumlah Karyawan</th>
                                    <th>Sudah Mengumpulkan</th>
                                    <th>Belum Mengumpulkan</th>
                                    <th>%</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($data['collection'] as $unitkerja)
                                        @php
                                            $progress = \Pkt\Domain\Rencana\Services\ProgressPercentageService::calculateProgressRencanaKaryawan($unitkerja->karyawan_count, $unitkerja->collected_count);
                                        @endphp
                                        <tr {!! rowLabelReportRencanaUnitKerja($progress['uncollected'], $unitkerja->collected_count) !!}>
                                            <td>{{ $data['numbering']++ }}</td>
                                            <td>{{ $unitkerja->Deskripsi }}</td>
                                            <td>{{ $unitkerja->karyawan_count }}</td>
                                            <td>{{ $unitkerja->collected_count }}</td>
                                            <td>{{ $progress['uncollected'] }}</td>
                                            <td>{{ $progress['progressPercentage'] }}%</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data.</td>
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
        $('#reportrealisasiunitkerja-table').DataTable({
            'pageLength': "{{ $data['collection']->count() }}",
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
        $('#reportrealisasiunitkerja-form').ready(function () {
            tahun();
        });
    </script>
@endsection