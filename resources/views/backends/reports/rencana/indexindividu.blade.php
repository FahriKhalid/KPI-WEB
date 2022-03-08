@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <div class="panel panel-default panel-box">
                    <div class="col-sm-12">
                        <div class="panel-title-box">Laporan Rencana KPI Individu Periode <span class="badge bg-info">{{ $data['periode'] }}</span></div>
                    </div>
                    <div class="filter-kpi">
                        <div class="col-sm-12">
                            <div class="row">
                                <form action="{{ route('report.rencana.kpiindividu.index') }}" method="get">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>NPK</label>
                                            <input type="text" name="npk" placeholder="NPK Karyawan" class="form-control" value="{{ request('npk') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Periode Rencana</label>
                                            <select name="tahunperiode" class="form-control">
                                                @foreach($data['periodeAktif'] as $periode)
                                                    <option value="{{ $periode->Tahun }}" {{ (request('tahunperiode') == $periode->Tahun) ? 'selected' : '' }}>{{ $periode->Tahun }}</option>
                                                @endforeach
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
                                        <a class="btn btn-yellow" href="{{ route('report.rencana.kpiindividu.index') }}">Reset</a>
                                    </div>
                                    <div class="col-sm-2 col-sm-offset-1">
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
                        <div class="alert alert-success" role="alert">Rencana Diterima <span class="badge pull-right">{{ $data['approvedCount'] }}</span></div>
                    </div>
                    <div class="col-sm-3">
                        <div class="alert alert-warning" role="alert">Dalam Proses <span class="badge pull-right">{{ $data['inprogressCount'] }}</span></div>
                    </div>
                    <div class="col-sm-3">
                        <div class="alert alert-danger" role="alert">Belum Membuat Rencana<span class="badge pull-right">{{ $data['karyawanCount'] - ($data['approvedCount'] + $data['inprogressCount']) }}</span></div>
                    </div>
                    <div class="panel-body">
                        <div class="margin-min-15" style="overflow-x: scroll; width: calc(100% + 30px);">
                            <table id="reportrencanaindividu-table" class="table table-striped" width="2000px">
                                <thead>
                                <tr>
                                    <th rowspan="2">No.</th>
                                    <th rowspan="2">NPK</th>
                                    <th rowspan="2" class="col-sm-2">Nama Lengkap</th>
                                    <th rowspan="2">Grade</th>
                                    <th rowspan="2" class="col-sm-2">Unit Kerja</th>
                                    <th rowspan="2" class="col-sm-2">Jabatan</th>
                                    <th colspan="3" class="text-center">Jumlah Item KPI</th>
                                    <th rowspan="2">Status</th>
                                    <th rowspan="2" class="col-sm-2">Keterangan</th>
                                    <th rowspan="2">Created On</th>
                                </tr>
                                <tr>
                                    <th class="col-sm-1">SR + Rutin</th>
                                    <th>TF</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($data['collection'] as $item)
                                        <tr {!! rowLabelReportRencanaIndividu($item->rencanakpi) !!}>
                                            <td>{{ $data['numbering']++ }}</td>
                                            <td>{{ $item->NPK }}</td>
                                            <td>{{ $item->NamaKaryawan }}</td>
                                            <td>{{ $item->organization->Grade }}</td>
                                            <td>{{ $item->organization->position->unitkerja->Deskripsi }}</td>
                                            <td>{{ $item->organization->position->PositionTitle }}</td>
                                            @php
                                                $rutinStrategi = 0;
                                                $taskForce = 0;
                                                if ($item->rencanakpi->count() > 0) {
                                                    $rutinStrategi = $item->rencanakpi[0]->detail()->nonTaskForce()->count();
                                                    $taskForce = $item->rencanakpi[0]->detail()->taskForce()->count();
                                                }
                                                $total = $rutinStrategi + $taskForce;
                                            @endphp
                                            <td class="text-center">{{ $rutinStrategi }}</td>
                                            <td class="text-center">{{ $taskForce }}</td>
                                            <td class="text-center">{{ $total }}</td>
                                            <td>{{ ($item->rencanakpi->count() > 0) ? $item->rencanakpi[0]->statusdokumen->StatusDokumen : '-' }}</td>
                                            <td>{{ ($item->rencanakpi->count() > 0) ? $item->rencanakpi[0]->Keterangan : '-' }}</td>
                                            <td>{{ ($item->rencanakpi->count() > 0) ? $item->rencanakpi[0]->CreatedOn : '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center"> Tidak ada data</td>
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
        $('#reportrencanaindividu-table').DataTable({
            'pageLength': 25,
            sDom: 't',
            columnDefs: [{targets: [0], orderable: false}],
            order: []
        });
    </script>
@endsection