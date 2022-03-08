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
                <form class="form-horizontal" method="post" action="{{ route('backend.master.periodeRealisasi.store') }}">
                    {!! csrf_field() !!}
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="panel-title-box">Tambah Periode Realisasi</div>
                            </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Nama Periode</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="IDJenisPeriode" id="JenisPeriode">
                                        <option>Pilih periode</option>
                                        @foreach($data['periode'] as $a)
                                            <option value="{{ $a->JenisPeriode }}">
                                                {{ $a->JenisPeriode}}
                                            </option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Tahun</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="Tahun">
                                        @foreach($data['tahunOpsi'] as $a)
                                            <option value="{{ $a }}">
                                                {{ $a }}
                                            </option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="periodecheck">
                                    
                                </div>

                        </div>
                    </div>
                    <div class="text-right save-container">
                        <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                        <a href="{{ route('backend.master.periodeRealisasi') }}" type="button" class="btn btn-default btn-orange">Batal</a>
                    </div>
                    
                    
                </form>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
<script>
    $('#JenisPeriode').change(function(){
        var jenisperiode = $('#JenisPeriode').val();
        //alert(jenisperiode)
            $.ajax({
                url: "{{ url('/jenisperiode/') }}/"+ jenisperiode,
                dataType: 'json',
                success: function(data){
                    var html = '';
                    
                    for (var i = 0; i < data.length; i++){
                        //alert('aaaa');
                        var check = '';
                        if(data[i].Aktif == 'true'){
                            check = ' checked';
                        }

                        html += '<div class="form-group">'+
                            '<label class="col-sm-2 control-label">'+data[i].KodePeriode+'</label>'+
                            '<div class="col-sm-7">'+
                                '<input type="checkbox" name="Status['+i+']" value="'+data[i].ID+'" '+check+'>'+
                            '</div>'+
                        '</div>';
                    }
                    $('#periodecheck').html(html);
                }
                
            });
    })
    function Periode() {
        alert('err');
            
    }
</script>
@endsection