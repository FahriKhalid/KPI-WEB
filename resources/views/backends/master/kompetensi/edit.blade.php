@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.master')
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
                    <form id="edit-kompetensi-form" class="form-horizontal" method="post" action="{{ route('backend.master.kompetensi.update', ['id' => $data['kompetensi']->ID]) }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_method" value="put">
                        <div class="panel panel-default panel-box panel-create">
                            <div class="panel-body">
                                <div class="col-sm-12">
                                    <div class="panel-title-box">Edit Kompetensi</div>
                                </div>

                                <div class="form-group">
                                        <label class="col-sm-2 control-label">PositionID</label>
                                        <div class="col-sm-7">
                                           <input type="text" name="PositionID" placeholder="ID Position untuk Kompetensi" class="form-control" value="{{ $data['kompetensi']->PositionID }}">
                                         </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Keterangan</label>
                                        <div class="col-sm-7">
                                            <input type="text" name="Keterangan" placeholder="Keterangan tambahan kompetensi" class="form-control" value="{{ $data['kompetensi']->Keterangan }}">
                                        </div>
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
                            <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                            <a href="{{ route('backend.master.kompetensi') }}" type="button" class="btn btn-default btn-orange">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=h15ws19lzjua0cwwlldwgyhfy3bra8xhir36p9rtgxhkadn6"></script>
@endsection