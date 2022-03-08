@inject('targetparser', 'Pkt\Domain\Rencana\Services\TargetParserService')
@extends('layouts.app')

@section('submenu')
@include('layouts.submenu.rencana')
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 margin-top-15">
            <ul class="kpi-menu">
                <li class="active">
                    <a href="{{ route('backends.kpi.realisasi.individu.editdetail', ['id' => $data['header']->ID]) }}">Realisasi KPI</a>
                </li>
                <li>
                    <a href="{{ route('backends.kpi.realisasi.individu.indexdocument', ['id' => $data['header']->ID]) }}">Dokumen</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 margin-top-30">
            <div class="panel panel-default panel-box panel-create">
                <div class="panel-body">
                    <div class="col-sm-12">
                        <div class="panel-title-box no-border">Input Realisasi KPI</div>
                        @include('vendor.flash.message')
                    </div>
                    <div class="col-sm-12">
                        <div class="panel-group panel-faq" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading" style="background-color: #337ab7;">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse" aria-expanded="true" aria-controls="collapse">
                                        <span class="fa-stack" style="float: right;margin-top: -0.5%;">
                                            <i class="fa fa-circle fa-inverse fa-stack-2x"></i>
                                            <i class="more-less fa fa-stack-2x fa-angle-right"></i>
                                        </span>
                                        <h4 class="panel-title" style="color: white;">
                                            <b>Data Karyawan</b>
                                        </h4>
                                    </a>
                                </div>
                                <div id="collapse" class="panel-collapse collapse row margin-bottom-15" role="tabpanel" aria-labelledby="heading">
                                    <div class="col-sm-12">
                                        <div class="panel panel-primary margin-top-15">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><b>Tahun</b><span class="pull-right">{{ $data['header']->Tahun }}</span></li>
                                                    <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['header']->NPK }}</span></li>
                                                    <li class="list-group-item"><b>Nama Karyawan</b><span class="pull-right">{{ $data['karyawan']->NamaKaryawan }}</span> </li>
                                                    <li class="list-group-item"><b>Grade</b><span class="pull-right">{{ $data['header']->Grade }}</span> </li>
                                                    <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ $data['karyawan']->organization->position->PositionTitle }}</span> </li>
                                                    <li class="list-group-item"><b>Kode Unit Kerja</b><span class="pull-right">{{ $data['karyawan']->organization->position->KodeUnitKerja }}</span></li>
                                                    <li class="list-group-item"><b>Unit Kerja</b><span class="pull-right">{{ $data['karyawan']->organization->position->unitkerja->Deskripsi }}</span> </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="panel panel-primary margin-top-15">
                                            <div class="panel-heading" style="background-color: #337ab7">Atasan Langsung</div>
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['atasanLangsung']->NPK }}</span></li>
                                                    <li class="list-group-item"><b>Nama</b><span class="pull-right">{{ $data['atasanLangsung']->NamaKaryawan }}</span></li>
                                                    <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ $data['header']->JabatanAtasanLangsung }}</span></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="panel panel-primary margin-top-15">
                                            <div class="panel-heading" style="background-color: #337ab7">Atasan Tak Langsung</div>
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['atasanTakLangsung']->NPK }}</span></li>
                                                    <li class="list-group-item"><b>Nama</b><span class="pull-right">{{ $data['atasanTakLangsung']->NamaKaryawan }}</span></li>
                                                    <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ $data['header']->JabatanAtasanBerikutnya }}</span></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    @if($data['header']->IDStatusDokumen == 1) 
                    <form class="form-horizontal" method="post" action="{{ route('realisasi.storeItem',['id' => $data['header']->ID]) }}">
                        {!! csrf_field() !!}
                        <input type="hidden" id="IDJenisPeriode" name="IDJenisPeriode" value="{{ $data['periode_aktif']->pluck('IDJenisPeriode')->first() }}">
                        <div class="col-sm-11 col-sm-offset-1">
                            <!--div class="form-group">
                                <label class="col-xs-2 control-label">Deskripsi KRA</label>
                                <div class="col-xs-6">
                                    <textarea name="DeskripsiKRA" class="form-control" rows="3">{{ old('DeskripsiKRA') }}</textarea>
                                </div>
                            </div--> 
                            <div class="form-group form-inline">
                                <label class="col-xs-2 control-label">Deskripsi KPI</label>
                                <div class="col-xs-6">
                                    <textarea name="DeskripsiKPI" class="form-control" rows="3" id="deskripsiKPI" style="min-width: 100%;">{{ old('DeskripsiKPI') }}</textarea>
                                </div> 
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Kelompok</label>
                                <div class="col-sm-5">
                                    <!--  {!! Form::select('IDKodeAspekKPI',['' => '-- Pilih Aspek KPI --'] + $data['aspekKpi'], old('IDKodeAspekKPI'), ['class' => 'form-control changeme', 'id'=>'IDKodeAspekKPI', 'onchange' => 'kodeAspek()']) !!} -->

                                    <select disabled name="IDKodeAspekKPI" id="IDKodeAspekKPI" class="form-control changeme" onchange="kodeAspek()">
                                        <option value="">-- Pilih Aspek KPI --</option>
                                        @foreach ($data['aspekKpi'] as $key => $item)
                                        <option value="{{ $key }}" {{ (strtolower($item) == 'task force' ? 'selected' : '') }} {{ (strtolower($item) != 'task force' ? 'disabled' : '') }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Sifat</label>
                                <div class="col-sm-5">
                                    {!! Form::select('IDJenisAppraisal', ['' => '-- Pilih Sifat --'] + $data['jenisAppraisal'], old('IDJenisAppraisal'), ['class' => 'form-control changeme','id'=>'IDJenisAppraisal']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Jenis KPI</label>
                                <div class="col-sm-5">
                                    {!! Form::select('IDPersentaseRealisasi', ['' => '-- Jenis KPI --'] + $data['persentaseRealisasi'], old('IDPersentaseRealisasi'), ['class' => 'form-control changeme','id'=>'IDPersentaseRealisasi']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Satuan</label>
                                <div class="col-sm-5">
                                    {!! Form::select('IDSatuan', ['' => '-- Pilih Satuan --'] + $data['satuan'], old('IDSatuan'), ['class' => 'form-control changeme','id'=>'IDSatuan']) !!}
                                </div>
                            </div>
                            @for($x = 1; $x <= $data['target']; $x++)
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Target {{$data['periodeTarget']}} - {{$x}}</label>
                                <div class="col-sm-5">
                                    <input type="number" step="any" min="0" lang="en" pattern="-?[0-9]+[\,.]*[0-9]+" name="Target{{ $targetparser->targetCount($data['target'])[$x-1] }}"  class="form-control double" placeholder="Target {{$x}}" value="{{ old('Target'.$targetparser->targetCount($data['target'])[$x-1])}}" required>
                                </div>
                            </div>
                            @endfor
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Bobot </label>
                                <div class="col-sm-2 ">
                                    <div class="input-group">
                                        <input type="text" name="Bobot" disabled class="form-control double" placeholder="Bobot" id="Bobot" value="2%" required>
                                        <span class="input-group-addon"> % </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Keterangan</label>
                                <div class="col-sm-6">
                                    <input type="text" name="Keterangan" class="form-control" placeholder="Keterangan (opsional)" id="Keterangan" value="{{old('Keterangan')}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-7 mt-3">
                                    <button type="submit" class="btn btn-default btn-blue">Tambah Task Force</button>
                                    <a href="{{ route('backends.kpi.realisasi.individu') }}" class="btn btn-default btn-orange">Batal</a>
                                </div>
                            </div>
                        </div> 
                    </form>  
                    @endif  
                </div> 
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-box">
                <div class="panel-body">
                    @if($data['header']->IDStatusDokumen == 1)

                    <div class="custom-button-container">  
                            <button class="btn btn-link" onclick="updateItemKPI()">
                                <img src="{{ asset('assets/img/ic_update.png') }}"> Edit Task Force
                            </button> 
                            <button class="btn btn-link" onclick="deleteItemKPI()">
                                <img src="{{ asset('assets/img/ic_add.png') }}" class="rotate-30"> Hapus Task Force
                            </button> 
                    </div>
                @endif

                @if(session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif

                <div class="" style="overflow-x:auto;"> 
                    <form class="form-horizontal" method="post" action="{{ route('backends.kpi.realisasi.individu.storenilai',['id' => $data['header']->ID]) }}">
                        {!! csrf_field() !!}
                        <table id="editrealisasi-table" class="table table-striped" style="width: 1500px">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th class="text-center" style="min-width:10px;"><input type="checkbox" id="checkAll"></th>
                                    <th>Aspek KPI</th>
                                    <th>Sifat</th>
                                    <th>Jenis KPI</th>
                                    <th>KRA</th>
                                    <th>KPI</th>
                                    <th>Bobot</th>
                                    <th>Satuan</th>
                                    <th>KPI Bawahan?</th>
                                    <th class="forcedWidth">Target {{ $data['periode']->KodePeriode }}</th>
                                    <th width='125' class='table_width'>Realisasi Target</th>
                                    <th>Persentase Realisasi</th>
                                    <th>Konversi Nilai</th>
                                    <th>Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalBobot = 0; $baris = 1; $strategis = 0.00; $rutin = 0.00; $taskforce = 0.00;
                                $totalKPI = 0.00; $arrTotalKPI = []; $no = 1;
                                @endphp
                                @forelse($data['alldetail'] as $detail)
                                <tr>
                                    <td></td> 
                                    <td class="text-center"> 
                                        @if($detail->detilrencana->aspekkpi->ID == 4)
                                            <input type="checkbox" name="check[]" value="{{ $detail->IDRencanaKPIDetil }}">
                                        @endif
                                    </td>
                                    <input type="hidden" name="idrealisasiitem[]" value="{{ $detail->ID }}">
                                    <td id="aspek{{$baris}}">{{ $detail->detilrencana->aspekkpi->AspekKPI }}</td>
                                    <td>{{ $detail->detilrencana->jenisappraisal->JenisAppraisal }}</td>
                                    <input type="hidden" name="IDPersentaseRealisasi[]" id="persentaserealisasi{{ $baris }}" value="{{ $detail->detilrencana->persentaserealisasi->ID }}">
                                    <td>{{ $detail->detilrencana->persentaserealisasi->PersentaseRealisasi }}</td>
                                    <td>{{ $detail->detilrencana->DeskripsiKRA }}</td>
                                    <td>{{ $detail->detilrencana->DeskripsiKPI }}</td>
                                    <td id="bobot{{$baris}}">{{ $detail->detilrencana->Bobot }}%</td>
                                    <td>{{ $detail->detilrencana->satuan->Satuan }}</td>
                                    <td>{{ ($detail->detilrencana->IsKRABawahan) ? 'Y' : 'N' }}</td>
                                    <input type="hidden" name="bobot[]" value="{{ $detail->detilrencana->Bobot }}">
                                    <td id="item{{$baris}}">{{ numberDisplay($detail->detilrencana->{'Target'.$data['targetRealization']}) }}</td>
                                    <input type="hidden" name="target[]" id="targetke-{{ $baris }}" value="{{ $detail->detilrencana->{'Target'.$data['targetRealization']} }}">
                                    <input type="hidden" name="idaspekkpi[]" value="{{ $detail->detilrencana->IDKodeAspekKPI }}">
                                    <input type="hidden" name="isKraBawahan[]" value="{{ $detail->detilrencana->IsKRABawahan }}">
                                    <td><input type="number" step="any" min="0" name="realization[]" class="form-control" {{ ($detail->detilrencana->IsKRABawahan) ? 'readonly' : 'required'}} onchange="realisasi({{$baris}},{{$data['target']}},{{$baris}})" id="target{{$baris}}realization{{$baris}}" value="{{ $detail->Realisasi }}">
                                    </td>
                                    @php
                                    $persentase = \Pkt\Domain\Realisasi\Services\TargetValueCalculationService::calculate($detail->detilrencana->{'Target'.$data['targetRealization']}, $detail->Realisasi, $detail->detilrencana->persentaserealisasi->ID);
                                    $convertion = \Pkt\Domain\Realisasi\Services\ConvertionService::convert($persentase);
                                    $final = \Pkt\Domain\Realisasi\Services\FinalValueService::calculate($convertion, $detail->detilrencana->Bobot);
                                    $arrTotalKPI[] = $final;
                                    @endphp
                                    @if($detail->detilrencana->aspekkpi->AspekKPI == 'Strategis')
                                    @php
                                    $strategis += $final;
                                    @endphp
                                    @elseif($detail->detilrencana->aspekkpi->AspekKPI == 'Rutin Struktural' || $detail->detilrencana->aspekkpi->AspekKPI == 'Rutin Operasional')
                                    @php
                                    $rutin += $final;
                                    @endphp
                                    @else
                                    @php
                                    $taskforce += $final;
                                    @endphp
                                    @endif
                                    <td id="kpi{{$baris}}">{{ number_format($persentase, 2) }}</td>
                                    <td id="konversi{{$baris}}">{{ number_format($convertion, 2) }}</td>
                                    <td id="nilaiakhir{{$baris}}"> {{ number_format($final, 2) }}</td>
                                </tr>
                                @php
                                $baris++;
                                @endphp
                                @empty
                                <tr>
                                    <td colspan="13" class="text-center">Tidak ada data detail rencana KPI</td>
                                </tr>
                                @endforelse
                                <input type="hidden" id="maxBaris" value="{{$baris}}">
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th colspan="13">Nilai KPI Strategis</th>
                                    <th id="strategis">{{ $strategis }}</th>
                                </tr>
                                <tr>
                                    <th colspan="13">Nilai KPI Rutin</th>
                                    <th id="rutin">{{ $rutin }}</th>
                                </tr>
                                <tr>
                                    <th colspan="13">Nilai KPI Task Force</th>
                                    <th id="task_force">{{ $taskforce }}</th>
                                </tr>
                                <tr>
                                    <th colspan="13">Total Nilai KPI</th>
                                    <th id="rata_nilai">{{ number_format(array_sum($arrTotalKPI), 2) }}</th>
                                    <input type="hidden" id="rataNilaiInput" name="NilaiAkhir">
                                </tr>
                            </tfoot>
                        </table>
                        <div class="text-right save-container margin-top-15">
                            <button type="submit" class="btn btn-default btn-blue">Simpan</button>
                            <a href="{{ route('backends.kpi.realisasi.individu') }}" class="btn btn-default btn-orange">Kembali</a>
                        </div> 
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
<script src="{{ asset('assets/js/realization.js') }}" type="text/javascript"></script>
<script>
    function toggleIcon(e) {
        $(e.target)
        .prev('.panel-heading')
        .find(".more-less")
        .toggleClass('fa-angle-right fa-angle-down');
    }
    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
    $('.panel-group').on('shown.bs.collapse', toggleIcon);

    $('#editrealisasi-table').DataTable({
        pageLength: "{{ $data['alldetail']->count() }}",
        sDom: 't',
        columns: [
        { orderable: false},
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        { orderable: false},
        { orderable: false},
        { orderable: false},
        { orderable: false},
        { orderable: false}
        ],
        fnRowCallback: function (nRow, aData, iDisplayIndex) {
            var index = iDisplayIndex +1;
            $('td:eq(0)',nRow).html(index);
            return nRow;
        },
        order: [],
    });

    function realisasi(id, max, row){
// calculate target value
var input = parseFloat($('#target'+id+'realization'+row).val());
var detail = parseFloat($('#targetke-'+row).val());
var idpersentaseRealisasi = $('#persentaserealisasi' + row).val();
var persentaserealisasi = calculatePersentaseRealisasi(detail, input, idpersentaseRealisasi);
$('#kpi'+row).html(persentaserealisasi);

var konversi = convertion(persentaserealisasi);
$('#konversi'+row).html(konversi);

var bobot = parseFloat($('#bobot'+row).html());
var akhir = nilaiAkhir(konversi, bobot);
$('#nilaiakhir'+row).html(akhir);

var maxBaris = parseFloat('{{ $data['alldetail']->count() }}');
var total1 = 0;
var total2 = 0;
var total4 = 0;
var aspek;
for(i = 1; i <= maxBaris; i++) {
    aspek = $('#aspek'+i).html();
    nilaiakhir = ($('#nilaiakhir'+i).html() == '') ? 0 : $('#nilaiakhir'+i).html();
    if(aspek == 'Strategis') {
        total1 += parseFloat(nilaiakhir);
    } else if(aspek == 'Rutin Struktural' || aspek == 'Rutin Operasional') {
        total2 += parseFloat(nilaiakhir);
    } else{
        total4 += parseFloat(nilaiakhir);
    }
}
total = total1 + total2 + total4;
$('#strategis').html(parseFloat(Math.round((total1)*100)/100).toFixed(2));
$('#rutin').html(parseFloat(Math.round((total2)*100)/100).toFixed(2));
$('#task_force').html(parseFloat(Math.round((total4)*100)/100).toFixed(2));
$('#rata_nilai').html(parseFloat(Math.round((total)*100)/100).toFixed(2));
$('#rataNilaiInput').val(parseFloat(Math.round((total)*100)/100).toFixed(2));
}


function updateItemKPI(){
    var checked = $(':checkbox:checked');
    if(checked.length == 0) {
        alert('Silakan pilih KPI terlebih dahulu.');
    }
    else if(checked.length !== 1)
    {
        alert('Pilih salah satu item KPI saja yang ingin di update.');
    }
    else {
        var id = checked.val(); 
        var url = "{{ route('backends.kpi.rencana.individu.edititem_realisasi',':id') }}";
        window.location.href = url.replace(':id',id);
    }
}

function deleteItemKPI(){
    // var checked = $(':checkbox:checked');
    // if(checked.length == 0) {
    //     alert('Silakan pilih KPI terlebih dahulu.');
    // }
    // else if(checked.length !== 1)
    // {
    //     alert('Pilih salah satu item KPI saja yang ingin di update.');
    // }
    // else {
    //     var id = checked.val(); 
    //     var url = "{{ route('backends.kpi.rencana.individu.edititem_realisasi',':id') }}";
    //     window.location.href = url.replace(':id',id);
    // }

    event.preventDefault();
    var checked = $(':checkbox:checked');
    var form = $('#modalform');
    var action = "{{ route('backends.kpi.realisasi.individu.deleteItem') }}";
    var modalTitle = 'Hapus Item Rencana KPI Individu';
    var modalContent = 'Apakah anda yakin untuk menghapus item rencana KPI Anda?';
    form.append('<input type="hidden" name="_method" value="delete" class="appendedInput">');
    callAction(form, checked, action, modalTitle, modalContent);
}


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
                    alert('Error : ' + xhr.statusText);
                    document.location.reload(true);
                }
            });
        });
    }
}
</script>
@endsection
