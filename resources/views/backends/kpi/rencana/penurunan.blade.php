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
    <form class="form-horizontal" id="create-penurunan-form" action="{{ route('backends.kpi.rencana.individu.storepenurunan', ['id' => $data['header']->ID]) }}" method="post">
        {!! csrf_field() !!}
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
                            @can('create-penurunan', $data['header'])
                                <div class="col-sm-12">
                                    <div class="panel-title-box no-border">Penurunan KPI</div>
                                </div>
                                <div class="col-sm-11 col-sm-offset-1">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">KPI</label>
                                        <div class="col-sm-7">
                                            <select class="form-control selectbox" id="selectkpiatasan" name="IDKPIAtasan">
                                                <option value="">--- Pilih KPI ---</option>
                                                @foreach($data['items'] as $item)
                                                    <option value="{{ $item->ID }}" {{ (old('IDKPIAtasan') == $item->ID) ? 'selected="selected"' : '' }}>{{ $item->DeskripsiKPI }}</option>
                                                @endforeach
                                            </select>

                                            <input type="hidden" id="IDJenisAppraisal" value="">
                                            <input type="hidden" id="target" name="">
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
                                                <td>#</td>
                                            </tr>
                                        </tbody>
                                        <tbody id="layout-parent">
                                            <tr class="form">
                                                <td class="numbering">1.</td>
                                                <td>
                                                    <select class="form-control selectbox select-bawahan" name="NPKBawahan[]">
                                                        <option value="">--- Pilih Bawahan ---</option>
                                                        @foreach($data['bawahan'] as $bawahan)
                                                            <option value="{{ $bawahan->NPK }}" {{ (old('NPKBawahan') == $bawahan->NPK) ? 'selected="selected"' : '' }}>({{ $bawahan->NPK }}) {{ $bawahan->NamaKaryawan }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    @for($x = 1; $x <= $data['target']; $x++)
                                                        <input type="hidden" value="" class="originalTarget{{ $targetparser->targetCount($data['target'])[$x-1] }}" name="originalTarget{{ $targetparser->targetCount($data['target'])[$x-1] }}" id="">
                                                                <input type="number" step="any" min="0" lang="en" pattern="-?[0-9]+[\,.]*[0-9]+" name="Target{{ $targetparser->targetCount($data['target'])[$x-1] }}[]" id="" class="form-control double target Target{{ $targetparser->targetCount($data['target'])[$x-1] }}" placeholder="Target {{$x}}" value="{{ old('Target'.$targetparser->targetCount($data['target'])[$x-1]) }}">
                                                    @endfor
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                    <input type="number" step="any" min="0" lang="en" pattern="-?[0-9]+[\,.]*[0-9]+" class="form-control double PersentaseKRA" name="PersentaseKRA[]" placeholder="Persentase KRA" value="100">
                                                        <span class="input-group-addon" id="basic-addon2">%</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button id="add-row" class="btn btn-success" type="button"><i class="fa fa-plus"></i></button>
                                                </td>
                                            </tr> 
                                        </tbody>
                                        <tbody id="layout-child">
                                            <tr class="form"> 
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td>
                                                    <span id="jumlah-target">min</span> / <span id="max-target">max</span>
                                                    <br> 
                                                    <small class="text-muted error-jumlah">Total target</small> 
                                                </td>
                                                <td>
                                                    <span class="jumlah-average">0%</span>  / <span>100%</span>
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
                                            <textarea class="form-control" name="Keterangan" rows="3" placeholder="Keterangan Penurunan KPI (Opsional)">{{ old('Keterangan') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-7 save-container">
                                            <button type="submit" class="btn btn-default btn-blue" id="savebtn">Tambah</button>
                                            <a href="{{ route('backends.kpi.rencana.individu') }}" class="btn btn-default btn-orange">Batal</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="border-bottom-container margin-bottom-15"></div>
                                </div>
                            @endcan
                            <div class="col-sm-12">
                                <div class="panel-title-box no-border no-margin-bottom">Penurunan KPI</div>
                            </div>
                            <div class="col-sm-9">
                                <div class="custom-button-container">
                                    <a href="#">
                                        <button class="btn btn-link" id="edititem-cascade">
                                            <img src="{{ asset('assets/img/ic_update.png') }}"> Edit / Update
                                        </button>
                                    </a>
                                    <a href="#">
                                        <button class="btn btn-link" id="delete-cascade">
                                            <img src="{{ asset('assets/img/ic_add.png') }}" class="rotate-30"> Hapus
                                        </button>
                                    </a>
                                </div>
                            </div>
                            <div class="margin-min-15" style="overflow-x: scroll; width: calc(100% + 30px);">
                                <table id="cascading-table" class="table table-striped" width="2000px">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>KPI</th>
                                        <th>Satuan</th>
                                        <th>Bobot</th>
                                        <th>NPK Bawahan</th>
                                        <th>Nama Karyawan</th>
                                        <th>Jenis Cascade</th>
                                        <th>Presentase KRA</th>
                                        @for($i=1;$i<=$data['target'];$i++)
                                            <th>Target {{ $data['periodeTarget'] }} - {{ $i }}</th>
                                        @endfor
                                        <th>Keterangan</th>
                                        <th>Created On</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($data['cascadeItems'] as $cascade)
                                        <tr>
                                            <td class="text-center"><input type="checkbox" name="check[]" value="{{ $cascade->ID }}" data-headerid="{{ $data['header']->ID }}"></td>
                                            <td>{{ $cascade->detailkpi->DeskripsiKPI }}</td>
                                            <td>{{ $cascade->detailkpi->satuan->Satuan }}</td>
                                            <td>{{ $cascade->detailkpi->Bobot }}%</td>
                                            <td>{{ $cascade->NPKBawahan }}</td>
                                            <td>{{ $cascade->NamaKaryawan }}</td>
                                            <td>{{ $cascade->detailkpi->jenisappraisal->JenisAppraisal }}</td>
                                            <td>{{ $cascade->PersentaseKRA }}%</td>
                                            @for($i=1;$i<=$data['target'];$i++)
                                                <td>{{ numberDisplay($cascade->{'Target'.$targetparser->targetCount($data['target'])[$i-1]}) }}</td>
                                            @endfor
                                            <td>{{ $cascade->Keterangan or '-' }}</td>
                                            <td>{{ $cascade->CreatedOn }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ 9 + $data['target'] }}" class="text-center">Tidak ada data penurunan KPI</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade modal-notification" id="actionConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close noCallButton" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('assets/img/ic_warning.png') }}" class="img-warning-modal">
                    <h4 class="modal-title" id="actionModalTitle"></h4>
                    <p id="actionModalContent"></p>
                    <form id="modalform" class="form-horizontal">
                        {!! csrf_field() !!}
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-yellow" id="callButton">Ya</button>
                    <button type="button" class="btn btn-default btn-no noCallButton" data-dismiss="modal">Tidak</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
    <script>
        @if($data['cascadeItems']->count() > 0) {
            $('#cascading-table').DataTable({
                lengthMenu: [
                    [5, 10, 20, 50, 100],
                    ["5", "10", "20", "50", "100"]
                ],
                columnDefs: [{targets: 0, orderable: false}],
                order: [],
                Dom: '<"top"l<"clear">><"bottom"p<"clear">>'
            });
        }
        @endif

        var autoCompleteTarget = true;
        var autoCompletePersentase = true;
        var max_target = 0;

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
                        
                        $("#target").val(data['Target' + i]);

                        if(autoCompleteTarget == true){
                            setTarget();
                        }else{
                             $('.Target' + i +':first').val(data['Target' + i]);
                        }
                        
                        
                        $("#max-target").html(data['Target' + i]);

                        // $('.originalTarget' + i).val(data['Target' + i]);
                        // if (target != null) {
                        //     target.val(data['Target' + i]);
                        //     target.attr('data-targetoriginal'+i, data['Target' + i]);
                        //     var result = calculateTargetByPercentage(data['Target' + i], $('#PersentaseKRA').val(), data.IDJenisAppraisal);
                        //     target.val(result);
                        // } 
                    }

                    max_target = data['Target12'];
                    sumTarget();
                    sumPersentaseKRA();
                });
            } else {
                $('#IDJenisAppraisal').val('');
                for(i=1;i<=12;i++) {
                    var target = $('.Target' + i);
                    if (target != null) {
                        target.val('');
                        $("#target").val('0.00');
                        target.removeAttr('data-targetoriginal'+i);
                    }
                }
            }
        });

        $('#PersentaseKRA').keyup(function() {
            if ($(this).val() <= 100) {
                for(i=1;i<=12;i++) {
                    var target = $('.Target' + i);
                    // if (target !== null && target.val() !== '') {
                    //     var result = calculateTargetByPercentage(target.data('targetoriginal'+i), $(this).val(), $('#IDJenisAppraisal').val());
                    //     target.val(result);
                    // }

                    if(target.val()){
                        var result = calculateTargetByPercentage(target.data('targetoriginal'+i), $(this).val(), $('#IDJenisAppraisal').val()); 
                        target.val(result);
                    }
                    
                }
            } else {
                $(this).val('')
                for(i=1;i<=12;i++) {
                    $('.Target' + i).val('');
                    
                }
                alert('Isian persentase KRA tidak boleh melebihi dari 100%.');
            }
        });

        function calculateTargetByPercentage(target, percentageKRA, jenisappraisal)
        {
            if (percentageKRA != '') {
                if (jenisappraisal == 2) { //kumulatif harus dibagi sesuai persentase
                    return (target / 100) * percentageKRA;
                }
            }
            return target;
        }

        $('#delete-cascade').click(function(event) {
            event.preventDefault();
            var checked = $(':checkbox:checked');
            var form = $('#modalform');
            var action = "{{ route('backends.kpi.rencana.individu.deletepenurunan') }}";
            var modalTitle = 'Hapus Penurunan Rencana KPI Individu';
            var modalContent = 'Apakah anda yakin untuk menghapus penurunan rencana KPI Anda?';
            form.append('<input type="hidden" name="_method" value="delete" class="appendedInput">');
            callAction(form, checked, action, modalTitle, modalContent);
        });

        $('.noCallButton').click(function () {
            $('#modalform').attr('action', '');
            $('.appendedInput').remove();
        });

        $('#edititem-cascade').click(function(event) {
            event.preventDefault();
            var checked = $('#cascading-table :checkbox:checked');
            if(checked.length == 0) {
                alert('Silakan pilih KPI terlebih dahulu.');
            }
            else if(checked.length !== 1)
            {
                alert('Pilih salah satu item KPI saja yang ingin di update.');
            }
            else {
                var idcascade = checked.val();
                var headerid = checked.data('headerid');
                var url = "{{ route('backends.kpi.rencana.individu.penurunan.edit', [':id', ':idcascade']) }}";
                window.location.href = url.replace(':id', headerid).replace(':idcascade', idcascade);
            }
        });

        // call action
        function callAction(form, checked, action, title, content) {
            if(checked.length === 0) {
                alert('Silakan pilih KPI terlebih dahulu.');
            } else {
                form.attr('action', action);
                checked.each(function(i) {
                    if($(this).val() !== 'on') {
                        form.append('<input type="hidden" name="id[]" value="'+ $(this).val() + '" class="appendedInput">')
                    }
                });
                $('#actionModalTitle').html(title);
                $('#actionModalContent').html(content);
                $('#actionConfirmModal').modal('show');
                $('#callButton').click(function(event) {
                    event.preventDefault();
                    $.ajax({
                        url: action,
                        type: 'post',
                        data: form.serialize().replace(/%5B%5D/g, '[]'),
                        success: function(result) {
                            if(result.status) {
                                document.location.reload(true);
                            } else {
                                alert(result.errors);
                                document.location.reload(true);
                            }
                        },
                        error: function(xhr) {
                            alert('Error: ' + xhr.statusText);
                            document.location.reload(true);
                        }
                    });
                });
            }
        } 

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

        $('#savebtn').click(function(event) 
        {
            event.preventDefault();
            var npkbawahan = $('select[name="NPKBawahan[]"]').val();
            var idkpiatasan = $('#selectkpiatasan').val();
            var table = $("#form-table-penurunan");
            var jenis = $('#IDJenisAppraisal').val();

            var jumlah_persentase = 0;
            table.find(".PersentaseKRA").each(function(){
                jumlah_persentase += parseFloat($(this).val());
            });

            var max_target = $("#target").val(); 
            var target = 0;

            table.find(".target").each(function(){ 
                target += parseFloat($(this).val()); 
            }); 

            var error = false;
            var array_npk = [];
            
            $('#form-table-penurunan .form').each(function()
            {  
                if($(this).find('select[name="NPKBawahan[]"]').val() == ''){ 
                    $(this).find('select[name="NPKBawahan[]"]').closest("td").addClass("bg-danger");
                    error = true;
                }else{
                    $(this).find('select[name="NPKBawahan[]"]').closest("td").removeClass("bg-danger");
                }

                if($(this).find('input[name="PersentaseKRA[]"]').val() == ''){ 
                    $(this).find('input[name="PersentaseKRA[]"]').closest("td").addClass("bg-danger");
                    error = true;
                }else{
                    $(this).find('input[name="PersentaseKRA[]"]').closest("td").removeClass("bg-danger");
                }

                if($(this).find('input[name="Target12[]"]').val() == ''){
                    $(this).find('input[name="Target12[]"]').closest("td").addClass("bg-danger");
                    error = true;
                }else{
                    $(this).find('input[name="Target12[]"]').closest("td").removeClass("bg-danger");
                }


                var array_npk_value = $(this).find('select[name="NPKBawahan[]"]').val();
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
            }else if(target.toFixed(2) > max_target){
                if(jenis == 2){
                    //alert('jumlah target tidak boleh lebih dari ' + target.toFixed(2) + ' - ' + max_target);
                    //return;
                }
            }

            $.ajax({
                url: "{{ route('backends.kpi.rencana.individu.penurunan.apicheckexistcascade') }}",
                type: 'post',
                data: {NPKBawahan: array_npk, IDKPIAtasan: idkpiatasan},
                success: function(result) {
                    if(result.status) {
                        if (confirm("Anda sudah menurunkan item KPI kepada NPK yang bersangkutan Apakah anda yakin?")) {
                            $('form#create-penurunan-form').submit();
                        }
                    } else {
                        $('form#create-penurunan-form').submit();
                    } 
                }
            });  
        });


        $("body").delegate("#add-row", "click", function(e){
            e.preventDefault();

            $('.selectbox').select2("destroy");

            var parent = $("#layout-parent").find("tr").clone();
            var child = $("#layout-child").append(parent);

            var buttonAction = child.find("button");
            var inputPersentaseKRA = child.find("tr:last").find(".PersentaseKRA");

            buttonAction.removeClass("btn-success").addClass("btn-danger");
            buttonAction.attr("id", "").addClass("remove-row");
            buttonAction.html("<i class='fa fa-minus'></i>");

            inputPersentaseKRA.val("");

            $('.selectbox').select2({
                theme: "bootstrap",
                width: "100%"
            });

            numbering();
        });

        $("body").delegate(".remove-row", "click", function(e){
            e.preventDefault();

            $(this).closest("tr").remove();

            numbering();
            sumPersentaseKRA();
            sumTarget(); 
        });
 

        function sumPersentaseKRA(){
            var jumlah = 0;

            $("#form-table-penurunan").find(".PersentaseKRA").each(function(){

                if($(this).val() != ''){
                    jumlah += parseFloat($(this).val());
                }
            });

            $(".jumlah-average").html(jumlah + '%')
        }

        function sumTarget(){
            var jenis = $('#IDJenisAppraisal').val();

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
                var target = $("#target").val();
                $("#jumlah-target").html(target);
            }
        }

        function setTarget(){
            var jenis = $('#IDJenisAppraisal').val();
            var target = $("#target").val();
            var jumlah_target = 0;

            if(jenis == 2) // kumulatif
            {
                var jumlah = $("#form-table-penurunan").find(".target").length;
                
                if(autoCompleteTarget == true){
                    $("#form-table-penurunan").find(".target").each(function(){ 
                        $(this).val(parseFloat(target/jumlah).toFixed(2));
                        $(this).prop("readonly", false);

                        jumlah_target += parseFloat($(this).val());
                    });

                    if(jumlah_target.toFixed(2) != 100){

                        var x = $("#layout-child").find("tr:last").find(".target").val();
                        var nilai = jumlah_target - x;
                        var hasil = max_target - nilai;

                        $("#layout-child").find("tr:last").find(".target").val(hasil.toFixed(2));
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

        function setPersentaseKRA(){
            var persentase = 100;
            var jumlah_persentase = 0;
            var jumlah = $("#form-table-penurunan").find(".PersentaseKRA").length;    

            if(autoCompletePersentase == true){
                $("#form-table-penurunan").find(".PersentaseKRA").each(function(){
                    $(this).val(parseFloat(persentase / jumlah).toFixed(2));
                    jumlah_persentase += parseFloat($(this).val());
                });

                if(jumlah_persentase.toFixed(2) != 100){

                    var x = $("#layout-child").find("tr:last").find(".PersentaseKRA").val();
                    var nilai = jumlah_persentase - x;
                    var hasil = 100 - nilai;

                    $("#layout-child").find("tr:last").find(".PersentaseKRA").val(hasil.toFixed(2));
                }
            }else{

            }
        }

        $("body").delegate(".PersentaseKRA", "keyup", function(){
            var input = $(this).val();
             
            sumPersentaseKRA();
        });
 

        $("body").delegate(".target", "keyup", function(){
            sumTarget();
        });

        function numbering(){
            var angka = 0; 
            $("#form-table-penurunan").find(".PersentaseKRA").each(function(){
                angka = angka + 1;
                $(this).closest("tr").find(".numbering").html(angka + '.')
            }); 

            setTarget();
            setPersentaseKRA();
            sumPersentaseKRA();
        }

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

        $("body").delegate("#checkbox-persentase", "click", function(){
            if($(this).prop("checked") == true){
                autoCompletePersentase = true;
                setPersentaseKRA();
                sumPersentaseKRA();
            } 
            else {
                autoCompletePersentase = false;

            } 
        });

    </script>
@endsection

















