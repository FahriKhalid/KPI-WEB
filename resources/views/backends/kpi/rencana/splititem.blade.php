@inject('targetparser', 'Pkt\Domain\Rencana\Services\TargetParserService')
@php
$abbrev = auth()->user()->abbreviation()!=null ? auth()->user()->abbreviation()->isUnitKerja():false;
@endphp

@extends('layouts.app')
@include('backends.kpi.rencana.kamus.kamus')
@section('submenu')
@include('layouts.submenu.rencana')
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 margin-top-15">
            <ul class="kpi-menu">
                <li>
                    <a href="{{ route('backends.kpi.rencana.individu.editdetail', ['id' => $data['header']->ID]) }}">Rencana KPI</a>
                </li>
                @if($abbrev)
                <li {!! (strpos(request()->path(), 'unitkerja') !== false) ? 'class="active"' : null !!}>
                    <a href="{{ route('backends.kpi.rencana.individu.unitkerja.index', ['id' => $data['header']->ID]) }}">KPI Unit Kerja</a>
                </li>
                @endif
                <li>
                    <a href="{{ route('backends.kpi.rencana.individu.pennurunan', ['id' => $data['header']->ID]) }}">Penurunan KPI</a>
                </li>
                <li>
                    <a href="{{ route('backends.kpi.rencana.individu.indexdocument', ['id' => $data['header']->ID]) }}">Dokumen</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="container">
    <div class=" "> 
        <div class="panel panel-default panel-box panel-create">
            <div class="panel-body"> 
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
        </div>
    </div>
</div> 
@endsection

@section('customjs')

@endsection
