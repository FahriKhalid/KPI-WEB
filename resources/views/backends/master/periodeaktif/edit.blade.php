@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.master')
@endsection

@section('content')
     <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                    <form id="edit-periodeaktif-form" class="form-horizontal" method="post" action="{{ route('backend.master.periodeaktif.update', ['id' => $data['periodeaktif']->first()->Tahun]) }}">
                        {!! csrf_field() !!}
                        <div class="panel panel-default panel-box panel-create">
                            <div class="panel-body">
                                <div class="col-sm-12">
                                    <div class="panel-title-box">Edit Periode </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Tahun</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="Tahun" id="Tahun" onchange="tanggal()">
                                            @foreach($data['tahunOpsi'] as $a)
                                                <option value="{{ $a }}" {{$data['periodeaktif']->first()->Tahun==$a?'selected':''}}>
                                                    {{ $a }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="thn" value="{{ $data['thn'] }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Jenis Periode</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="JenisPeriode" id="JenisPeriode" onchange="tanggal()">
                                            <option>Pilih periode</option>
                                            @foreach($data['periode'] as $a)
                                                <option value="{{ $a->JenisPeriode }}" {{$data['periodeaktif']->first()->jenisperiode->JenisPeriode==$a->JenisPeriode?'selected':''}}>
                                                    {{ $a->JenisPeriode}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="jenisperiod" value="{{$data['periodeaktif']->first()->jenisperiode->JenisPeriode}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-7 col-sm-offset-2" id="periodecheck">
                                    </div>
                                </div>
                            </div>
                            <div>
                            <!--<input type="hidden" class="datetimepicker" value="{{ date('Y-m-d h:i:s') }}" name="StartDate">-->
                            <!--<input type="hidden" value="{{ date('Y-m-d h:i:s') }}" name="EndDate">-->
                                <input type="hidden" value="{{ auth('web')->user()->NPK }}" name="CreatedBy">
                                <input type="hidden" value="{{ date('Y-m-d h:i:s') }}" name="CreatedOn">
                            </div>
                        </div>
                        <div class="text-right save-container">
                            <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                            <a href="{{ route('backend.master.periodeaktif') }}" type="button" class="btn btn-default btn-orange">Batal</a>
                        </div>
                    </form>
            </div>
        </div>
     </div>
@endsection

@section('customjs')
    <script>
        function tanggal(){
            var jenisperiode = $('#JenisPeriode').val();
            var tahun = $('#Tahun').val();
            var thn = $('#thn').val();
            var jenisperiod = $('#jenisperiod').val();
            var url = "{{ route('backend.jenisperiode.Tahun',[':jenisPeriode',':tahun']) }}".replace(':jenisPeriode', jenisperiode).replace(':tahun',thn);
            $.ajax({
                url: url,
                dataType: 'json',
                success: function(data){
                    var html = '';

                    for (var i = 0; i < data['periode'].length; i++){
                        if( jenisperiod == jenisperiode ){
                            if(thn == tahun){
                                var aktif = data['aktif'][i].Aktif==true?'checked':'';
                                var mulai = data['aktif'][i].StartDate;
                                var selesai = data['aktif'][i].EndDate;
                                var keterang = data['aktif'][i].Keterangan;
                                var namaPeriode = data['aktif'][i].NamaPeriode;
                                if(aktif != 'checked'){
                                    var test = 'disabled';
                                }
                                else{
                                    var test = '';
                                }
                            }
                            else {
                                var aktif = '';
                                var mulai = '';
                                var selesai = '';
                                var keterang = '';
                                var test = 'disabled';
                            }
                            
                        }
                        else {
                            var aktif = '';
                            var mulai = '';
                            var selesai = '';
                            var keterang = '';
                            var test = 'disabled';
                        }
                        html +=
                            '<div class="panel panel-primary">' +
                            '<div class="panel-heading">'+data['periode'][i].NamaPeriodeKPI+'</div>' +
                            '<div class="panel-body">' +
                            '<div class="form-group">'+
                                '<label class="col-sm-2 control-label">Nama Periode</label>'+
                                '<div class="col-sm-10">'+
                                    '<input type="text" id="NamaPeriode['+i+']" class="form-control" name="NamaPeriode['+i+']" placeholder="Nama dari Periode Aktif KPI" value="'+ namaPeriode +'">'+
                                '</div>'+
                            '</div>'+
                            '<div class="form-group">'+
                                '<label class="col-sm-2 control-label">Aktif</label>'+
                                '<div class="col-sm-10">'+
                                    '<input type="checkbox" id="Aktif['+i+']" name="Aktif['+i+']" onchange="aktifasi('+i+')" onload="aktifasi('+i+')"'+aktif+'>'+
                                '</div>'+
                            '</div>'+

                            '<div class="form-group">'+
                            '<label class="col-sm-2 control-label">'+'Mulai '+'</label>'+
                            '<div class="col-sm-4">'+
                            '<input class="form-control StartDate['+i+']" type="date" min="'+tahun+'-01-01" max="'+tahun+'-12-31" name="StartDate['+i+']" id="StartDate['+i+']" value="'+mulai+'" onchange="end('+i+')" '+test+'>'+
                            '</div>'+
                            '<label class="col-sm-2 control-label" >'+'Selesai '+'</label>'+
                            '<div class="col-sm-4">'+
                            '<input class="form-control EndDate['+i+']" type="date" min="'+mulai+'" max="'+tahun+'-12-31" name="EndDate['+i+']" id="EndDate['+i+']" value="'+selesai+'" '+test+'>'+
                            '</div>'+
                            '</div>'+

                            '<div class="form-group">'+
                            '<label class="col-sm-2 control-label">Keterangan</label>'+
                            '<div class="col-sm-10">'+
                            '<input type="text" name="Keterangan['+i+']" id="Keterangan['+i+']" placeholder="Tambah Keterangan" class="form-control" value="'+keterang+'" '+test+'>'+
                            '<input type="hidden" name="urutan['+i+']" value="'+i+'">'+
                            '</div>'+
                            '</div>'+

                            '</div>'+
                            '</div>';
                    }
                    $('#periodecheck').html(html);
                }

            });
        }
        function Periode() {
            alert('err');
        }
        $('#edit-periodeaktif-form').ready(function () {
            tanggal();
        });

    function aktifasi($i){
        if(!document.getElementById("Aktif["+$i+"]").checked)
        {
            document.getElementById("StartDate["+$i+"]").disabled = true;
            document.getElementById("EndDate["+$i+"]").disabled = true;
            document.getElementById("Keterangan["+$i+"]").disabled = true;
            
            
        }
        else
        {
            document.getElementById("StartDate["+$i+"]").disabled = false;
            document.getElementById("EndDate["+$i+"]").disabled = false;
            document.getElementById("Keterangan["+$i+"]").disabled = false;
        }
    }

    function end($i){
        var mulai = document.getElementById("StartDate["+$i+"]").value;
        document.getElementById("EndDate["+$i+"]").min = mulai;
    }
    </script>
@endsection