@extends('layouts.app')

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
                <form id="create-kompetensi-form" class="form-horizontal" method="post" action="{{ route('backend.master.kompetensi.store') }}">
                    {!! csrf_field() !!}
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="panel-title-box">Tambah Kompetensi</div>
                            </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">PositionID</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="PositionID" placeholder="ID Position untuk Kompetensi" class="form-control" value="{{ old('PositionID') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Keterangan</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="Keterangan" placeholder="Tambah Keterangan" class="form-control" value="{{ old('Keterangan') }}">
                                    </div>
                                </div>

                                <div>
                                    <input type="hidden" value="{{ auth('web')->user()->ID }}" name="CreatedBy">
                                    <input type="hidden" value="{{ date('Y-m-d h:i:s') }}" name="CreatedOn">
                                    <input type="hidden" value="{{ auth('web')->user()->ID }}" name="UpdatedBy">
                                    <input type="hidden" value="{{ date('Y-m-d h:i:s') }}" name="UpdatedOn">
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="text-right save-container">
                        <button type="submit" id="save" class="btn btn-default btn-blue">Simpan</button>
                        <a href="{{ route('backend.master.kompetensi') }}" type="button" class="btn btn-default btn-orange">Batal</a>
                    </div>
                    
                    
                </form>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=h15ws19lzjua0cwwlldwgyhfy3bra8xhir36p9rtgxhkadn6"></script>
    <script>
        $(function(){
            $('#save').one('click', function() {  
                $(this).attr('disabled','disabled');
                $('#create-kompetensi-form').submit();
            });
        });
    </script>
@endsection