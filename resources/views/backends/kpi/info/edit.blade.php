@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.master')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <form class="form-horizontal" method="post" action="{{ route('backend.kpi.update', ['id' => $data->ID]) }}" enctype="multipart/form-data">
                    @include('vendor.flash.message')
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="put">
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="panel-title-box">Edit Data Info KPI</div>
                            </div>
                            <div class="col-sm-11 col-sm-offset-1">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Judul</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="Judul" placeholder="Judul" required value="{{ $data->Judul }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Tanggal Publish</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control date" name="Tanggal_publish" placeholder="Tanggal publish informasi" required value="{{ date('Y-m-d h:i:s', strtotime($data->Tanggal_publish)) }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Tanggal Berakhir</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control date" name="Tanggal_berakhir" placeholder="Tanggal berakhir informasi (opsional)" value="{{ (! is_null($data->Tanggal_berakhir)) ? date('Y-m-d h:i:s', strtotime($data->Tanggal_berakhir)) : null }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Informasi</label>
                                    <div class="col-sm-7">
                                        <textarea rows="10" name="Informasi">{!! $data->Informasi !!}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Gambar Baru</label>
                                    <div class="col-sm-7">
                                        <img id="photo" src="{{ !empty($data->Gambar)?asset('images/kpiinfo/'.$data->Gambar):null }}" class="img-responsive img-rounded img-thumbnail" alt="Tidak ada gambar yang ditampilkan">
                                        <div class="margin-top-15">
                                            <input id="uploadFile" class="form-control" placeholder="Choose File" value="{{$data->Gambar}}"readonly />
                                            <div class="fileUpload btn btn-default btn-yellow">
                                                <span>Pilih Gambar</span>
                                                <input type="file" name="Gambar" id="uploadBtn" class="upload">
                                            </div>
                                        </div>
                                        <input type="hidden" name="old_file" value="{{$data->Gambar}}">
                                        <span id="helpBlock" class="help-block">(Opsional) Berkas harus berupa gambar dan ukuran maksimal 500kb. Unggah jika hanya ingin mengganti gambar lama.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right save-container">
                        <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                        <a href="{{ route('backend.kpi.info') }}" class="btn btn-default btn-orange">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=h15ws19lzjua0cwwlldwgyhfy3bra8xhir36p9rtgxhkadn6"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
    <script src="{{ asset('assets/js/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/daterangepicker.js') }}"></script>
    <script>
        $('.date').daterangepicker( {
            format: 'YYYY-MM-DD HH:MM:ss',
            singleDatePicker: true,
            locale: {
                format: 'YYYY-MM-DD HH:MM:ss'
            }
        });

        function loadImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#photo').attr('src', e.target.result)
                };
                $('#photo').show();
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#uploadBtn").change(function(){
            loadImage(this);
            $('#uploadFile').val(this.value.split(/(\\|\/)/g).pop());
        });
    </script>
@endsection