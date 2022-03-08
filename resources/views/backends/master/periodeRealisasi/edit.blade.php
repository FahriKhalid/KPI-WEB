@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.pengaturan')
@endsection

@section('content')
     <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                 @if($errors->any())
                    <div class="alert alert-danger alert-dismissable alert-important">
                        <ul>
                            <button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @include('vendor.flash.message')
                    <form id="edit-perioderealisasi-form" class="form-horizontal" method="post" action="{{ route('backend.master.periodeRealisasi.update') }}">
                        {!! csrf_field() !!}
                        <div class="panel panel-default panel-box panel-create">
                            <div class="panel-body">
                                <div class="col-sm-12">
                                    <div class="panel-title-box">Edit Periode Realisasi</div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Tahun</label>
                                    <div class="col-sm-7">
                                        <label class="col-sm-2 col-sm-offset-1 control-label form">{{$data['tahun']}}</label>
                                        <input type="hidden" name="Tahun" value="{{$data['tahun']}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Periode</label>
                                    <div class="col-sm-7">
                                        <label class="col-sm-2 col-sm-offset-1 control-label form">{{$data['periodeRealisasi'][0]->JenisPeriode}}</label>
                                    </div>
                                </div>
                                    <div class="form-group">
                                    <label class="col-sm-2 control-label" >Aktif</label>
                                    </div>
                                    
                                    <?php $i = 0;?>
                                    @foreach($data['periodeRealisasi'] as $value)
                                        <div class="form-group">
                                            <label class="col-sm-2 col-sm-offset-1 control-label form">{{$value->KodePeriode}}</label>
                                            <div class="col-sm-5">
                                                <input type="checkbox" name="IDJenisPeriode[{{$i}}]" value="{{$value->ID}}" {{ in_array($value->ID,$data['array'])?'checked':'' }} >
                                            <?php $i++;?>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                        <div class="text-right save-container">
                            <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                            <a href="{{ route('backend.master.periodeRealisasi') }}" type="button" class="btn btn-default btn-orange">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=h15ws19lzjua0cwwlldwgyhfy3bra8xhir36p9rtgxhkadn6"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
@endsection