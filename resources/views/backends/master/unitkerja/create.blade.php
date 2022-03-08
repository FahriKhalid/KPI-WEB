@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.master')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <form id="create-unitkerja-form" class="form-horizontal" method="post" action="{{ route('backend.master.unitkerja.store') }}">
                    {!! csrf_field() !!}
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="panel-title-box">Tambah Data Unit Kerja</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Kode Unit Kerja</label>
                                <div class="col-sm-7">
                                    <input type="text" name="CostCenter" placeholder="Kode Unit Kerja" class="form-control" value="{{ old('CostCenter') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nama Unit Kerja</label>
                                <div class="col-sm-7">
                                    <input type="text" name="Deskripsi" placeholder="Nama Unit Kerja" class="form-control" value="{{ old('Deskripsi') }}">
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-2 control-label">Keterangan</label>
                                <div class="col-sm-7">
                                    <textarea rows="10" name="Keterangan">{!! old('Keterangan') !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-7">
                                <ul class="container-check">
                                    <li>
                                        <input type="radio" id="f-option" name="Aktif" value="1" checked="checked">
                                        <label for="f-option">Aktif</label>
                                        <div class="check"></div>
                                    </li>
                                    <li>
                                        <input type="radio" id="s-option" name="Aktif" value="0">
                                        <label for="s-option">Non Aktif</label>
                                        <div class="check"><div class="inside"></div></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                                {{--<div class="form-group">--}}
                                    {{--<label class="col-sm-2 control-label">Field1</label>--}}
                                    {{--<div class="col-sm-7">--}}
                                        {{--<input type="text" name="Field1" placeholder="Custom field 1" class="form-control" value="{{ old('Field1') }}">--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group">--}}
                                    {{--<label class="col-sm-2 control-label">Field2</label>--}}
                                    {{--<div class="col-sm-7">--}}
                                        {{--<input type="text" name="Field2" placeholder="Custom field 2" class="form-control" value="{{ old('Field2') }}">--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group">--}}
                                    {{--<label class="col-sm-2 control-label">Field3</label>--}}
                                    {{--<div class="col-sm-7">--}}
                                        {{--<input type="text" name="Field3" placeholder="Custom field 3" class="form-control" value="{{ old('Field3') }}">--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group">--}}
                                    {{--<label class="col-sm-2 control-label">Field4</label>--}}
                                    {{--<div class="col-sm-7">--}}
                                        {{--<input type="text" name="Field4" placeholder="Custom field 4" class="form-control" value="{{ old('Field4') }}">--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group">--}}
                                    {{--<label class="col-sm-2 control-label">Field5</label>--}}
                                    {{--<div class="col-sm-7">--}}
                                        {{--<input type="text" name="Field5" placeholder="Custom field 5" class="form-control" value="{{ old('Field5') }}">--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                        <div>
                            <input type="hidden" value="{{ auth('web')->user()->ID }}" name="CreatedBy">
                            <input type="hidden" value="{{ date('Y-m-d h:i:s') }}" name="CreatedOn">
                            <input type="hidden" value="{{ auth('web')->user()->ID }}" name="UpdatedBy">
                            <input type="hidden" value="{{ date('Y-m-d h:i:s') }}" name="UpdatedOn">
                        </div>
                    </div>
                    <div class="text-right save-container">
                        <button type="submit" id="save" class="btn btn-default btn-blue">Simpan</button>
                        <a href="{{ route('backend.master.unitkerja') }}" type="button" class="btn btn-default btn-orange">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=h15ws19lzjua0cwwlldwgyhfy3bra8xhir36p9rtgxhkadn6"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
    <script>
        $(function(){
            $('#save').one('click', function() {  
                $(this).attr('disabled','disabled');
                $('#create-unitkerja-form').submit();
            });
        });
    </script>
@endsection