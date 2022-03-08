<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dataTables.bootstrap.min.css') }}"/>
<script type="text/javascript" src="{{ asset('assets/js/jquery-2.2.4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/dataTables.bootstrap.min.js') }}"></script>
<style>
    thead{
        background-color: rgb(51, 122, 183);
        color: white;
    }
    th{
        min-width: 150px;
    }
</style>
@include('vendor.loader.loader',['phase'=>''])
@extends('vendor.loader.loader',['phase'=>'Memuat kamus ...'])
<div class="modal fade modal-notification" id="modalkamus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="panel-title-box no-border">Kamus KPI</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="">
                        <div class="message-kamus-null alert alert-danger">
                            <ul>
                                <button class="close message-kamus-null-dismiss" aria-hidden="true"  type="button">x</button>
                                <li style="list-style:none;">Tidak ada kamus yang dipilih</li>
                            </ul>
                        </div>
                        <table id="kamus-table" class="table table-striped">
                            <thead>
                            <tr>
                                <th rowspan="2" style="min-width:10px;"></th>
                                <th rowspan="2">Kode Reg</th>
                                <th rowspan="2" style="min-width: 250px;">Judul KPI</th>
                                <th rowspan="2">Kode Unit Kerja</th>
                                <th colspan="2" style="text-align: Center;">Indikator</th>
                                <th rowspan="2" style="min-width: 250px;">Deskripsi</th>
                                <th rowspan="2">Satuan</th>
                                <th rowspan="2">Kelompok</th>
                                <th rowspan="2">Persentase Realisasi</th>
                                <th rowspan="2">Rumus</th>
                                <th rowspan="2">Sumber Data</th>
                                <th rowspan="2">Periode Laporan</th>
                                <th rowspan="2">Jenis</th>
                                <th rowspan="2">Sifat</th>
                                <th rowspan="2">Catatan</th>
                            </tr>
                            <tr>
                                <th>Hasil</th>
                                <th>Kinerja</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="text-right save-container margin-top-15">
                    <button type="button" id="kamusAjaxOk" class="btn btn-default btn-blue">Pilih</button>
                    <button type="button" class="btn btn-default btn-yellow" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function modalKamus(){
        $.ajax({success:function(){
            $('.message-kamus-null').hide();
            $('#modalkamus').modal('show');
        }});
    }
    function disablecontent() {
        $('#deskripsiKPI').prop("readonly", true);
        $('#IDKodeAspekKPI').prop("disabled", true);
        $('#IDJenisAppraisal').prop("disabled", true);
        $('#IDPersentaseRealisasi').prop("disabled", true);
        $('#IDSatuan').prop("disabled", true);
    }
    $('#kamus-table').DataTable({
        processing:true,
        serverSide: true,
        lengthMenu: [[5, 10], [5, 10]],
        autoWidth : false,
        ajax:{
            headers:{
                        'X-CSRF-TOKEN': '{!! csrf_token() !!}'
                    },
            url:'{!! route('backends.kpi.rencana.individu.kamus') !!}',
            method:'post'
        },
        order: [[2, 'desc']],
        columns: [
            { data: 'Aksi', orderable: false},
            { data: 'KodeRegistrasi', name: 'KodeRegistrasi' },
            { data: 'KPI', name: 'KPI' },
            { data: 'KodeUnitKerja', name: 'KodeUnitKerja'},
            { data: 'IndikatorHasil', name:'IndikatorHasil'},
            { data: 'IndikatorKinerja', name:'IndikatorKinerja'},
            { data: 'Deskripsi', name:'Deskripsi'},
            { data: 'satuan.Satuan', name: 'satuan.Satuan'},
            { data: 'aspekkpi.AspekKPI', name: 'aspekkpi.AspekKPI'},
            { data: 'persentaserealisasi.PersentaseRealisasi', name: 'persentaserealisasi.PersentaseRealisasi'},
            { data: 'Rumus', name: 'Rumus'},
            { data: 'SumberData', name: 'SumberData'},
            { data: 'PeriodeLaporan', name: 'PeriodeLaporan'},
            { data: 'Jenis', name: 'Jenis'},
            { data: 'jenisappraisal.JenisAppraisal', name: 'jenisappraisal.JenisAppraisal'},
            { data: 'Keterangan', name: 'Keterangan'}
        ]
    });
    $('.kamusAjax')/*.DataTable().rows().every*/.ready(function (/*rowIdx, tableLoop, rowLoop*/) {
        var KodeRegistrasi = '{{ isset($data['detail']->KodeRegistrasiKamus)?$data['detail']->KodeRegistrasiKamus:old('KodeRegistrasiKamus') }}';
        $('input[type=radio][name=KodeRegistrasiKamus][class=kamusAjax][value="'+ KodeRegistrasi +'"]').prop('checked', true);
    });
    $('#detailrencanaform').ready(function () {
        if($('input[type=\'radio\'][name=\'KodeRegistrasiKamus\']:checked').val()!=null){
            disablecontent();
            $('#message-kamus').attr({style: "visibility: show;"});
        }
    });
    $('.message-kamus-null-dismiss').click(function () {
        $('.message-kamus-null').hide();
    });
    $('#kamusAjaxOk').click(function(event) {
        event.preventDefault();
        var value = $('.kamusAjax:checked').val();
        var deskripsiKPIText = $('#deskripsiKPI');
        var IDKodeAspekKPI = $('#IDKodeAspekKPI');
        var IDJenisAppraisal = $('#IDJenisAppraisal');
        var IDPersentaseRealisasi = $('#IDPersentaseRealisasi');
        var IDSatuan = $('#IDSatuan');
        if (value == null){
            deskripsiKPIText.val('');
            IDKodeAspekKPI.val('');
            IDJenisAppraisal.val('');
            IDPersentaseRealisasi.val('');
            IDSatuan.val('');
            //alert('Tidak ada kamus yang dipilih');
            $('.modal').animate({
                scrollTop: $('.modal-dialog').offset().top
            }, 500);
            $('.message-kamus-null').show();
        }
        else{
            $.ajax({
                url: "{{ url('/kpi/kamus/api/find') }}?koderegistrasi=" + value,
                success: function (data) {
                    deskripsiKPIText.html(data.KPI).text();
                    IDKodeAspekKPI.val(data.aspekkpi.ID).change();
                    IDJenisAppraisal.val(data.jenisappraisal.ID).change();
                    IDPersentaseRealisasi.val(data.persentaserealisasi.ID).change();
                    IDSatuan.val(data.satuan.ID).change();
                    disablecontent();
                    $('#message-kamus').attr({style: "visibility: show;"});
                    $('#modalkamus').modal('hide');
                }
            });
        }
    })
</script>