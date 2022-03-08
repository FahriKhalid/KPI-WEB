@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <form class="form-horizontal" method="post" action="{{ route('narration.store') }}">
                    {!! csrf_field() !!}
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="panel-title-box">Narasi Beranda</div>
                            </div>
                            <div class="col-sm-11 col-sm-offset-1">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Konten</label>
                                    <div class="col-sm-7">
                                        <textarea rows="10" name="Content">{!! $data['artikel']->Content or '' !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right save-container">
                        <button type="submit" class="btn btn-default btn-blue">Simpan</button>
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
        .mce-stack-layout-item{
            border-right: 1px solid #CCC;
        }
    </style>
@endsection