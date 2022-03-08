@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <form class="form-horizontal" method="post" action="{{ route('artikel.store') }}">
                    {!! csrf_field() !!}
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="panel-title-box">Tambah Data Narasi Home</div>
                            </div>
                            <div class="col-sm-11 col-sm-offset-1">
                                {{--<div class="form-group">--}}
                                    {{--<label class="col-sm-2 control-label">Judul</label>--}}
                                    {{--<div class="col-sm-7">--}}
                                        {{--<input type="text" name="Title" class="form-control" placeholder="Judul Artikel" value="{{ old('Title') }}" required>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Konten</label>
                                    <div class="col-sm-7">
                                        <textarea rows="10" name="Content">{!! old('Content') !!}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Aktif</label>
                                    <div class="col-sm-4">
                                        <ul class="container-check">
                                            <li>
                                                <input type="radio" id="s-option" name="Aktif" value="0" {{old('Aktif')==false?'checked':''}}>
                                                <label for="s-option">Tidak</label>
                                                <div class="check"><div class="inside"></div></div>
                                            </li>
                                            <li>
                                                <input type="radio" id="f-option" name="Aktif" value="1" {{old('Aktif')==true?'checked':''}}>
                                                <label for="f-option">Ya</label>
                                                <div class="check"></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right save-container">
                        <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                        <a href="{{ route('artikel') }}" type="button" class="btn btn-default btn-orange">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=h15ws19lzjua0cwwlldwgyhfy3bra8xhir36p9rtgxhkadn6"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
    <style>
        .mce-edit-area{
            border-right: 1px solid #CCC!important;
        }
    </style>
@endsection