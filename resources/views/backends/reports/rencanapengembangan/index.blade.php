@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <div class="panel panel-default panel-box">
                    <div class="col-sm-12">
                        <div class="panel-title-box">Laporan Rencana Pengembangan Periode <span class="badge bg-info">{{ $data['periode'] }}</span></div>
                    </div>
                    <div class="filter-kpi">
                        <div class="col-sm-12">
                            <div class="row">
                                <form action="{{ route('report.rencanapengembangan.index') }}" method="get">
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
                                    <div class="col-sm-1">
                                        <button class="btn btn-primary" type="submit">Cari</button>
                                    </div>
                                    <div class="col-sm-1">
                                        <a class="btn btn-yellow" href="{{ route('report.rencanapengembangan.index') }}">Reset</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="alert alert-info" role="alert">Total Follow UP <span class="badge pull-right">{{ $data['collection']->total() }}</span></div>
                    </div>
                    <div class="col-sm-9">
                        <div class="alert alert-warning" role="alert">Data yang ditampilkan hanya Rencana Pengembangan yang bersifat TRAINING.</div>
                    </div>
                    <div class="panel-body">
                        <div class="margin-min-15" style="overflow-x: scroll; width: calc(100% + 30px);">
                            <table id="reportrencanapengembangan-table" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>NPK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Jabatan</th>
                                        <th>Unit Kerja</th>
                                        <th>Nilai KPI</th>
                                        <th>Rencana Pengembangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($data['collection'] as $item)
                                    <tr>
                                        <td>{{ $data['numbering']++ }}</td>
                                        <td>{{ $item->headerrealisasikpi->NPK }}</td>
                                        <td>{{ $item->headerrealisasikpi->karyawan->NamaKaryawan }}</td>
                                        <td>{{ $item->headerrealisasikpi->karyawan->organization->position->PositionTitle }}</td>
                                        <td>{{ $item->headerrealisasikpi->karyawan->organization->position->unitkerja->Deskripsi }}</td>
                                        <td>{{ $item->headerrealisasikpi->NilaiAkhir }}</td>
                                        <td>{{ $item->RencanaPengembangan }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="7">Tidak ada data.</td>
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
        $('#reportrencanapengembangan-table').DataTable({
            'pageLength': 25,
            sDom: 't'
        });
    </script>
@endsection
