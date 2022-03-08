@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.master')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @include('vendor.flash.message')
                <form id="create-periodeaktif-form" class="form-horizontal" method="post" action="{{ route('backend.master.periodeaktif.store') }}">
                    {!! csrf_field() !!}
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="panel-title-box">Tambah Periode </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tahun</label>
                                <div class="col-sm-7">
                                    <select class="form-control" name="Tahun" id="Tahun" onchange="tanggal()">
                                    @foreach($data['tahunOpsi'] as $a)
                                        <option value="{{ $a }}" {{old('Tahun')==$a?'selected':''}}>
                                            {{ $a }}
                                        </option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Jenis Periode</label>
                                <div class="col-sm-7">
                                    <select class="form-control" name="JenisPeriode" id="JenisPeriode" onchange="tanggal()">
                                    <option>Pilih periode</option>
                                    @foreach($data['periode'] as $a)
                                        <option value="{{ $a->JenisPeriode }}" {{old('JenisPeriode')==$a->JenisPeriode?'selected':''}}>
                                            {{ $a->JenisPeriode}}
                                        </option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-7 col-sm-offset-2" id="periodecheck">
                                </div>
                            </div>
                        </div>
                                <div>
                                    <input type="hidden" value="{{ auth('web')->user()->NPK }}" name="CreatedBy">
                                    <input type="hidden" value="{{ date('Y-m-d h:i:s') }}" name="CreatedOn">
                                </div>
                    </div>
                    <div class="text-right save-container">
                        <button type="submit" onclick="" id="save" class="btn btn-default btn-blue">Simpan</button>
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
            $.ajax({
                url: "{{ route('backend.jenisperiode',':jenisPeriode') }}".replace(':jenisPeriode', jenisperiode),
                dataType: 'json',
                success: function(data){
                    var html = '';
                    for (var i = 0; i < data['periode'].length; i++){
                        html +=
                            '<div class="panel panel-primary">' +
                                '<div class="panel-heading">'+data['periode'][i].NamaPeriodeKPI+'</div>' +
                                '<div class="panel-body">' +
                                    '<div class="form-group">'+
                                        '<label class="col-sm-2 control-label">Nama Periode</label>'+
                                        '<div class="col-sm-10">'+
                                            '<input type="text" id="NamaPeriode['+i+']" class="form-control" name="NamaPeriode['+i+']" placeholder="Nama dari Periode Aktif KPI">'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="form-group">'+
                                        '<label class="col-sm-2 control-label">Aktif</label>'+
                                        '<div class="col-sm-10">'+
                                            '<input type="checkbox" id="Aktif['+i+']" name="Aktif['+i+']" onchange="aktifasi('+i+')">'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="form-group">'+
                                        '<label class="col-sm-2 control-label">'+'Mulai '+'</label>'+
                                        '<div class="col-sm-4">'+
                                            '<input class="form-control" id="StartDate['+i+']" type="date" min="'+tahun+'-01-01" max="'+tahun+'-12-31" name="StartDate['+i+']" onchange="end('+i+')" disabled>'+
                                        '</div>'+
                                        '<label class="col-sm-2 control-label" >'+'Selesai '+'</label>'+
                                        '<div class="col-sm-4">'+
                                            '<input class="form-control" id="EndDate['+i+']" type="date"  min="'+tahun+'-01-01" max="'+tahun+'-12-31" name="EndDate['+i+']" disabled>'+
                                        '</div>'+
                                    '</div>'+

                                    '<div class="form-group">'+
                                        '<label class="col-sm-2 control-label">Keterangan</label>'+
                                        '<div class="col-sm-10">'+
                                            '<input type="text" name="Keterangan['+i+']" id="Keterangan['+i+']" placeholder="Tambah Keterangan" class="form-control" disabled>'+
                                            '<input type="hidden" name="urutan['+i+']" value="'+i+'">'+
                                        '</div>'+
                                    '</div>'+

                                '</div>'+
                            '</div>';
//                        $(".Aktif["+i+"]:checked").change(function(){
//                            alert(this.val());
//                            if(this.val()=='true'){
//                                $('.StartDate['+i+']').prop("disabled", true);
//                                $('.EndDate['+i+']').prop("disabled", true);
//                            }
//                        });
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
            document.getElementById("EndDate["+$i+"]").disabled = false;
            document.getElementById("Keterangan["+$i+"]").disabled = false;
            document.getElementById("StartDate["+$i+"]").disabled = false;
            
        }
    }

    function end($i){
        var mulai = document.getElementById("StartDate["+$i+"]").value;
        document.getElementById("EndDate["+$i+"]").min = mulai;
        
        // document.getElementById("EndDate["+$i+"]").required = true;
        // document.getElementById("Keterangan["+$i+"]").required = true;
    }

    $(function(){
        $('#save').one('click', function() {  
            $(this).attr('disabled','disabled');
            $('#create-periodeaktif-form').submit();
        });
    });
</script>
@endsection