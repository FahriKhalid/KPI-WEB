@inject('targetparser', 'Pkt\Domain\Rencana\Services\TargetParserService')
@extends('layouts.app')

@php
    $abbrev = auth()->user()->abbreviation()!=null ? auth()->user()->abbreviation()->isUnitKerja():false;
@endphp

@section('submenu')
    @include('layouts.submenu.rencana')
@endsection

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-12 col-sm-12 margin-top-15">
                <ul class="kpi-menu">
                    <li>
                        <a href="{{ route('backends.kpi.rencana.individu.editdetail', ['id' => $data['header']->ID]) }}">Rencana KPI</a>
                    </li>
                    @if($abbrev)
                        <li {!! (strpos(request()->path(), 'unitkerja') !== false) ? 'class="active"' : null !!}>
                            <a href="{{ route('backends.kpi.rencana.individu.unitkerja.index', ['id' => $data['header']->ID]) }}">KPI Unit Kerja</a>
                        </li>
                    @endif
                    <li class="active">
                        <a href="{{ route('backends.kpi.rencana.individu.pennurunan', ['id' => $data['header']->ID]) }}">Penurunan KPI</a>
                    </li>
                    <li>
                        <a href="{{ route('backends.kpi.rencana.individu.indexdocument', ['id' => $data['header']->ID]) }}">Dokumen</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <form class="form-horizontal" id="form-edit-penurunan">
        {!! csrf_field() !!}
        <input type="hidden" name="_method" value="put">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 margin-top-30">
                    <div class="panel panel-default panel-box panel-create">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="border-bottom-container margin-bottom-15">
                                    @include('vendor.flash.message')
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">Data Karyawan</div>
                                        <div class="panel-body">
                                            <ul class="list-group">
                                                <li class="list-group-item"><strong>Tahun</strong><span class="pull-right">{{ $data['header']->Tahun }}</span></li>
                                                <li class="list-group-item"><strong>NPK</strong><span class="pull-right">{{ $data['karyawan']->NPK }}</span></li>
                                                <li class="list-group-item"><strong>Nama Karyawan</strong><span class="pull-right">{{ $data['karyawan']->NamaKaryawan }}</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="panel-title-box no-border">Penurunan KPI</div>
                            </div>
                            <div class="col-sm-11 col-sm-offset-1 margin-bottom-15">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">KPI</label>
                                    <div class="col-sm-7">
                                        <select class="form-control selectbox" id="selectkpiatasan" name="IDKPIAtasan">
                                            <option value="">--- Pilih KPI ---</option>
                                            @foreach($data['items'] as $item)
                                                <option value="{{ $item->ID }}" {{ ($data['cascadeitem']->IDKPIAtasan == $item->ID) ? 'selected="selected"' : '' }}>{{ $item->DeskripsiKPI }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-10 col-sm-offset-1">

                                <table class="table panel panel-default panel-box" id="form-table-penurunan"> 
                                    <tbody>
                                        <tr>
                                            <td>No</td>
                                            <td>Nama</td>
                                            <td>Target THN-1  
                                                <div style="float: right;">
                                                    <input type="checkbox" checked id="checkbox-target">

                                                    <span>auto</span>
                                                </div>
                                            </td>
                                            <td>Persentase KRA
                                                <div style="float: right;">
                                                    <input type="checkbox" checked id="checkbox-persentase">

                                                    <span>auto</span>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" onclick="add_row()" class="btn btn-success"><i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody id="layout-parent">
                                        @php
                                        $persentase = 0;
                                        $target_min = 0;
                                        $target_max = 0;
                                        $target_max = $data['parentKPI']->Target12;
                                        @endphp
                                        @foreach($data['itemTurunan'] as $item) 

                                        @php
                                        $persentase += $item->PersentaseKRA;
                                        @endphp

                                        @if($item->detailkpi->IDJenisAppraisal == 2)
                                            @php
                                            $target_min += $item->Target12;
                                            @endphp
                                        @else
                                            @php 
                                            $target_min = $item->Target12;
                                            @endphp
                                        @endif

                                        <tr class="form">
                                            <td class="numbering"> {{ $loop->iteration }}.  </td>
                                            <td>
                                                <input type="hidden" name="ID[]" value="{{ $item->ID }}">
                                                <select class="form-control selectbox select-bawahan" name="NPKBawahan[]">
                                                    <option value="">--- Pilih Bawahan ---</option>
                                                    @foreach($data['bawahan'] as $bawahan)
                                                        <option value="{{ $bawahan->NPK }}" {{ ( $item->NPKBawahan == $bawahan->NPK) ? 'selected="selected"' : '' }}>({{ $bawahan->NPK }}) {{ $bawahan->NamaKaryawan }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td> 
                                                @for($x = 1; $x <= $data['target']; $x++)
                                                    <input type="hidden" value="" class="originalTarget{{ $targetparser->targetCount($data['target'])[$x-1] }}" name="originalTarget{{ $targetparser->targetCount($data['target'])[$x-1] }}" id="">
                                                            <input type="number" step="any" min="0" lang="en" pattern="-?[0-9]+[\,.]*[0-9]+" name="Target{{ $targetparser->targetCount($data['target'])[$x-1] }}[]" 
                                                                {{ $item->detailkpi->IDJenisAppraisal == 2 ? '' : 'readonly'}}
                                                                class="form-control double target Target{{ $targetparser->targetCount($data['target'])[$x-1] }}" placeholder="Target {{$x}}" value="{{ $item->Target12 }}">
                                                                
                                                @endfor
                                            </td>
                                            <td>
                                                
                                                <div class="input-group">
                                                <input type="number" step="any" min="0" lang="en" pattern="-?[0-9]+[\,.]*[0-9]+" class="form-control double PersentaseKRA" name="PersentaseKRA[]" placeholder="Persentase KRA" value="{{ $item->PersentaseKRA }}">
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger delete" did="{{ $item->ID }}" type="button"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr> 
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td>
                                                <span id="jumlah-target">{{ toFixed($target_min, 2) }}</span> / <span id="max-target">{{ toFixed($target_max, 2) }}</span>
                                                <br> 
                                                <small class="text-muted error-jumlah">Total target</small> 
                                            </td>
                                            <td>
                                                <span class="jumlah-average">{{ $persentase }}%</span>  / <span>100%</span>
                                                <br> 
                                                <small class="text-muted error-jumlah">Total persentase</small>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="col-sm-11 col-sm-offset-1"> 
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Keterangan</label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" name="Keterangan" rows="3" placeholder="Keterangan Penurunan KPI (Opsional)">{{ $data['cascadeitem']->Keterangan }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-7 save-container">
                                        <button type="submit" class="btn btn-default btn-blue">Update</button>
                                        <a href="{{ route('backends.kpi.rencana.individu.pennurunan', ['id' => $data['header']->ID]) }}" class="btn btn-default btn-orange">Batal</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="border-bottom-container margin-bottom-15"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close noCallButton" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="https://ekpi-pilot.pupukkaltim.com/assets/img/ic_warning.png" class="img-warning-modal">
                    <br><br>
                    <h4 class="modal-title" id="actionModalTitle">Hapus Item Turunan Rencana KPI Individu</h4>
                    <br>
                    <p id="actionModalContent">Apakah anda yakin untuk menghapus item turunan rencana KPI Anda?</p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-yellow" id="delete">Ya</button>
                    <button type="button" class="btn btn-default btn-no noCallButton" data-dismiss="modal">Tidak</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('customjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
    <script>
        var id_turunan = '{{ $id }}';
        var auto_persentase = true;
        var autoCompleteTarget = true;

        $("#checkbox-persentase").click(function(){
            if($(this).is(":checked")){
                auto_persentase = true;
                setPersentaseKRA();
                sumPersentaseKRA();
            }else{
                auto_persentase = false;
            } 
        });

        $("body").delegate("#checkbox-target", "click", function(){
            if($(this).prop("checked") == true){
                autoCompleteTarget = true;
                setTarget();
                sumTarget();
            } 
            else {
                autoCompleteTarget = false;
            } 
        });


        $('.selectbox').select2({
            theme: "bootstrap",
            width: "100%"
        });

        $('#selectkpiatasan').change(function() {
            var idkpi = $(this).val();
            var apiurl = "{{ route('backends.kpi.rencana.individu.penurunan.apidetailkpi', [':iddetailrencana']) }}";
            if (idkpi != '') {
                $.get(apiurl.replace(':iddetailrencana', idkpi), function(data) {
                    $('#IDJenisAppraisal').val(data.IDJenisAppraisal);
                    for(i=1;i<=12;i++) {
                        var target = $('#Target' + i);
                        if (target != null) {
                            target.val(data['Target' + i]);
                            target.attr('data-targetoriginal'+i, data['Target' + i]);
                            var result = calculateTargetByPercentage(data['Target' + i], $('#PersentaseKRA').val(), data.IDJenisAppraisal);
                            target.val(result);
                        }
                    }
                });
            } else {
                $('#IDJenisAppraisal').val('');
                for(i=1;i<=12;i++) {
                    var target = $('#Target' + i);
                    if (target != null) {
                        target.val('');
                        target.removeAttr('data-targetoriginal'+i);
                    }
                }
            }
        });

        $('#PersentaseKRA').keyup(function() {
            if ($(this).val() <= 100) {
                for(i=1;i<=12;i++) {
                    var target = $('#Target' + i);
                    if (target !== null && target.val() !== '') {
                        var result = calculateTargetByPercentage(target.data('targetoriginal'+i), $(this).val(), $('#IDJenisAppraisal').val());
                        target.val(result);
                    }
                }
            } else {
                alert('Isian persentase KRA tidak boleh melebihi dari 100%.');
            }
        });

        function calculateTargetByPercentage(target, percentageKRA, jenisappraisal)
        {
            if (percentageKRA != '') {
                if ('{{ $data['parentKPI']->jenisappraisal}}' == 2) { //kumulatif harus dibagi sesuai persentase
                    return (target / 100) * percentageKRA;
                }
            }
            return target;
        }

        function add_row()
        {
            var html = $("#layout-parent").find("tr:last");   

            html.find(".selectbox").select2("destroy");

            var clone = html.clone();

            clone.find('button:last').removeClass("delete").addClass("remove-row") 
                .find('i').removeClass("fa-trash").addClass("fa-minus");

            clone.find("select[name='NPKBawahan[]']").attr("name", "new_NPKBawahan[]");
            clone.find("input[name='Target12[]']").attr("name", "new_Target12[]");
            clone.find("input[name='PersentaseKRA[]']").attr("name", "new_PersentaseKRA[]"); 

            clone.find("input").val("");
            clone.find("select").val("");

            var append = $("#layout-parent").append(clone);

            append.find(".selectbox").select2({
                theme : 'bootstrap',
                width : '100%'
            });

            numbering();
        }

        $("body").delegate(".remove-row", "click", function(){
            $(this).closest("tr").remove();

            numbering();
        });


        function numbering(){
            var angka = 0; 
            $("#layout-parent").find("tr").each(function(){
                angka = angka + 1;
                $(this).closest("tr").find(".numbering").html(angka + '.')
            }); 

            setTarget();
            sumTarget();
            setPersentaseKRA();
            sumPersentaseKRA();
        }

        function setPersentaseKRA()
        {
            var persentase = 100;
            var jumlah_persentase = 0;
            var jumlah = $("#form-table-penurunan").find(".PersentaseKRA").length;   

            if(auto_persentase == true){
                $("#layout-parent").find(".PersentaseKRA").each(function(){
                    $(this).val(parseFloat(persentase / jumlah).toFixed(2));
                    jumlah_persentase += parseFloat($(this).val());
                });

                if(jumlah_persentase.toFixed(2) != 100){

                    var x = $("#layout-parent").find("tr:last").find(".PersentaseKRA").val();
                    var nilai = jumlah_persentase - x;
                    var hasil = 100 - nilai;

                    $("#layout-parent").find("tr:last").find(".PersentaseKRA").val(hasil.toFixed(2));
                }

            }else{

            }
        }

        function sumPersentaseKRA(){
            var jumlah = 0;

            $("#layout-parent").find(".PersentaseKRA").each(function(){
                if($(this).val() != ''){
                    jumlah += parseFloat($(this).val());
                }
            });

            $(".jumlah-average").html(jumlah + '%')
        }

        $("body").delegate(".select-bawahan", "change", function(){

            var array = [];
            $("#layout-parent").find("tr").each(function(){
                var val = $(this).find("select").val();
                if(val != ""){
                    array.push(val);
                } 
            }); 

            if(checkIfDuplicateExists(array) === true){
                $(this).val("").change(); 
                alert("Bawahan duplikat, silahkan pilih bawahan lainnya");
            }
        });

        function checkIfDuplicateExists(w){
            return new Set(w).size !== w.length 
        }

        function setTarget()
        {
            var jenis = '{{ $data['cascadeitem']->detailkpi->IDJenisAppraisal }}';
            var target = '{{ $data['cascadeitem']->detailkpi->Target12 }}';

            if(jenis == 2) // kumulatif
            {
                var jumlah = $("#form-table-penurunan").find(".target").length;
                var jumlah_target = 0;
                
                if(autoCompleteTarget == true){
                    $("#form-table-penurunan").find(".target").each(function(){ 
                        $(this).val(parseFloat(target/jumlah).toFixed(2));
                        $(this).prop("readonly", false);

                        jumlah_target += parseFloat($(this).val());
                    });

                    if(jumlah_target.toFixed(2) != 100){

                        var x = $("#layout-parent").find("tr:last").find(".target").val();
                        var nilai = jumlah_target - x;
                        var hasil = '{{ toFixed($target_max, 2) }}' - nilai;

                        $("#layout-parent").find("tr:last").find(".target").val(hasil.toFixed(2));
                    }

                } else {
                    if(!(jumlah < 2)){
                        $("#form-table-penurunan").find(".target:last").val("");
                        $(this).prop("readonly", false);
                    }
                }
            }else{

                $("#form-table-penurunan").find(".target").each(function(){ 
                    $(this).val(target);
                    $(this).prop("readonly", true);
                });

            }
        }

        function sumTarget(){
            var jenis = '{{ $data['cascadeitem']->detailkpi->IDJenisAppraisal }}';

            if(jenis == 2){
                var jumlah = 0;

                $("#form-table-penurunan").find(".target").each(function(){
                    if($(this).val() != ''){
                        jumlah += parseFloat($(this).val())
                    }
                }); 

                $("#jumlah-target").html(jumlah.toFixed(2)) 
            }
            else
            {
                var target = '{{ $data['cascadeitem']->detailkpi->Target12 }}';
                $("#jumlah-target").html(target);
            }
        }

        $(document).on("submit", "#form-edit-penurunan", function(e){
            e.preventDefault();


            //var npkbawahan = $('.select-bawahan').val();
            var idkpiatasan = '{{ $data['cascadeitem']->IDKPIAtasan }}';
            var table = $("#form-edit-penurunan");
            var jenis = '{{ $data['cascadeitem']->detailkpi->IDJenisAppraisal }}';

            var jumlah_persentase = 0;
            table.find(".PersentaseKRA").each(function(){
                jumlah_persentase += parseFloat($(this).val());
            });

            var max_target = '{{ toFixed($target_max, 2) }}'; 
            var target = 0;

            table.find(".target").each(function(){ 
                target += parseFloat($(this).val()); 
            }); 

            var error = false;
            var array_npk = [];
            
            $('#layout-parent tr').each(function()
            {    
                if($(this).find('.select-bawahan').val() == ''){ 
                    $(this).find('.select-bawahan').closest("td").addClass("bg-danger");
                    error = true; 
                }else{
                    $(this).find('.select-bawahan').closest("td").removeClass("bg-danger");
                }

                if($(this).find('.PersentaseKRA').val() == ''){ 
                    $(this).find('.PersentaseKRA').closest("td").addClass("bg-danger");
                    error = true;
                }else{
                    $(this).find('.PersentaseKRA').closest("td").removeClass("bg-danger");
                }

                if($(this).find('.target').val() == ''){
                    $(this).find('.target').closest("td").addClass("bg-danger");
                    error = true;
                }else{
                    $(this).find('.target').closest("td").removeClass("bg-danger");
                }


                var array_npk_value = $(this).find('.select-bawahan').val();
                if(array_npk_value != null){
                    array_npk.push(array_npk_value);   
                }
                
            });  
            
            if(error){
                alert('form ada yang kosong, mohon diperiksa kembali');
                return;

            }else if(hasDuplicates(array_npk)){
                alert('ada bawahan yang duplikat');
                return;

            }else if(jumlah_persentase > 100){
                alert('jumlah persentase tidak boleh lebih dari 100%');
                return;
            }

            else if(target.toFixed(2) > max_target){
                if(jenis == 2){
                    alert('jumlah target tidak boleh lebih dari ' + target.toFixed(2) + ' ---- ' + max_target);
                    return;
                }
            }

            $.ajax({
                url : '{{ route('backends.kpi.rencana.individu.penurunan.update', ['id' => $data['header']->ID, 'idcascade' => $data['cascadeitem']->ID]) }}',
                type : 'POST',
                data : new FormData(this),
                contentType : false,
                processData : false,
                dataType : 'json',
                beforeSend : function(){

                },
                success : function(resp){
                    if(resp.status == true){
                        alert('Update data berhasil');
                        location.href = '{{ url('kpi/rencana/individu/'.$id.'/penurunan') }}'
                    } else {
                        alert('Update data tidak berhasil');
                    }
                },
                error : function(){

                }
            });
        });

        var tr_delete = null;

        $("body").delegate(".delete", "click", function(e){
            e.preventDefault();
            tr_delete = $(this).closest("tr");
            var id = $(this).attr("did"); 
            $("#delete").attr("did", id);
            $("#modal-notification").modal("show");
        });

        $("body").delegate("#delete", "click", function(){
            var id = $(this).attr("did"); 
            $("#modal-notification").modal("hide");
            tr_delete.remove();
            numbering();

            
            $.ajax({
                url : '{{ url('kpi/rencana/individu/penurunan/delete') }}',
                type : 'GET',
                data : { _token : "{{ csrf_token() }}", id : id },
                dataType : 'json', 
                success : function(resp){
                        if(resp.status == 'success'){
                            alert(resp.message);
                        } else {
                            alert(resp.message);
                        }
                },
                error : function(){

                }
            });
        });


        $("body").delegate(".target", "keyup", function(){
            sumTarget();
        });

        $("body").delegate(".PersentaseKRA", "keyup", function(){            
            sumPersentaseKRA();
        });
    
        function hasDuplicates(array) {
            var valuesSoFar = Object.create(null);
            for (var i = 0; i < array.length; ++i) {
                var value = array[i];
                if (value in valuesSoFar) {
                    return true;
                }
                valuesSoFar[value] = true;
            }
            return false;
        }
 

    </script>
@endsection