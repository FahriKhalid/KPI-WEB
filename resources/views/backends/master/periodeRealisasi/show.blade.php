@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.pengaturan')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="panel panel-default panel-box">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box">Detail Data Periode Aktif</div>
                            <div class="row">
                                @if($errors->any())
                                <div class="alert alert-danger alert-dismissable alert-important">
                                    <ul>
                                    </ul>
                                </div>
                                @endif
                                @include('vendor.flash.message')
                                <table style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Jenis Periode</th>
                                            <th>Kode Periode</th>
                                            <th>Nama Periode KPI</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $info)
                                        <tr>
                                            <td>{{ $info->JenisPeriode }}</td>
                                            <td>{{ $info->KodePeriode }}</td> 
                                            <td>{{ $info->NamaPeriodeKPI }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
<script>
</script>
@endsection