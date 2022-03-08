@inject('targetparser', 'Pkt\Domain\Rencana\Services\TargetParserService')
@php
    $abbrev = auth()->user()->abbreviation()!=null ? auth()->user()->abbreviation()->isUnitKerja():false;
@endphp

@extends('layouts.app')
@include('backends.kpi.rencana.kamus.kamus')
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
                    <li class="active">
                        <a href="{{ route('backends.kpi.rencana.individu.unitkerja.index', ['id' => $data['header']->ID]) }}">KPI Unit Kerja</a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('backends.kpi.rencana.individu.pennurunan', ['id' => $data['header']->ID]) }}">Penurunan KPI</a>
                    </li>
                    <li>
                        <a href="{{ route('backends.kpi.rencana.individu.indexdocument', ['id' => $data['header']->ID]) }}">Dokumen</a>
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
                            <div class="panel-title-box no-border">Form Item KPI<span class="label label-info pull-right">{{ strtoupper($data['header']->statusdokumen->StatusDokumen) }}</span></div>
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
                    </div>
                </div>
                <div class="panel panel-default panel-box panel-create">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel-title-box no-border no-margin-bottom">Item KPI</div>
                        </div>
                        @if($data['header']->IDStatusDokumen == 1)
                            <div class="col-sm-4">
                                <div class="custom-button-container">
                                    <a href="#">
                                        <button class="btn btn-link" id="updateItemKPI">
                                            <img src="{{ asset('assets/img/ic_update.png') }}"> Edit / Update
                                        </button>
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div class="margin-min-15" style="overflow-x: scroll; width: calc(100% + 30px);">
                            <table id="rencanaunitkerja-table" class="table table-striped" width="100%">
                                <thead>
                                <tr>
                                    <th style="min-width: 10px;">No.</th>
                                    @if($data['header']->IDStatusDokumen == 1)
                                        <th class="text-center" style="min-width:10px;"><input type="checkbox" id="checkAll"></th>
                                    @endif
                                    <th>Kelompok</th>
                                    <th>KRA</th>
                                    <th>KPI</th>
                                    <th>Bobot</th>
                                    <th>Satuan</th>
                                    @for($i=1; $i<=$data['target']; $i++)
                                        <th>Target {{$data['periodeTarget']}} - {{ $i }}</th>
                                    @endfor
                                    <th>Keterangan</th>
                                    <th>Sifat</th>
                                    <th>Jenis KPI</th>
                                    <th>Sbg KPI Bawahan</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $totalBobot = 0;
                                @endphp
                                @forelse($data['alldetail'] as $detail)
                                    <tr>
                                        <td></td>
                                        @if($data['header']->IDStatusDokumen == 1)
                                            <td class="text-center"><input type="checkbox" name="check[]" value="{{ $detail->ID }}"></td>
                                        @endif
                                        <td>{{ $detail->aspekkpi->AspekKPI }}</td>
                                        <td>{{ $detail->DeskripsiKRA }}</td>
                                        <td>{{ $detail->DeskripsiKPI }}</td>
                                        <td>{{ $detail->Bobot }}%</td>
                                        <td>{{ $detail->satuan->Satuan }}</td>
                                        @for($i=1; $i<=$data['target']; $i++)
                                            <td>{{ (! is_null($detail->{'Target'.$targetparser->targetCount($data['target'])[$i-1]})) ? numberDisplay($detail->{'Target'.$targetparser->targetCount($data['target'])[$i-1]}) : '-'}}</td>
                                        @endfor
                                        <td>{{ $detail->kpiatasan->Keterangan or $detail->Keterangan }}</td>
                                        <td>{{ $detail->jenisappraisal->JenisAppraisal }}</td>
                                        <td>{{ $detail->persentaserealisasi->PersentaseRealisasi }}</td>
                                        <td class="text-center">{!! $detail->IsKRABawahan ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>' !!}</td>
                                        @php
                                            $totalBobot += $detail->Bobot;
                                        @endphp
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{$data['header']->IDStatusDokumen == 1? 9 + $data['target'] : 8 + $data['target']}}" class="text-center">Tidak ada data detail rencana KPI</td>
                                    </tr>
                                @endforelse
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="5">Total Bobot</th>
                                    <th>{{ $totalBobot }}%</th>
                                </tr>
                                <tr>
                                    <th colspan="5">Total Item KPI</th>
                                    <th>{{ $data['alldetail']->count() }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center">
                            @if($data['header']->isDraft())
                            <button class="btn btn-default btn-blue" id="register" data-idrencana="{{ $data['header']->ID }}"><i class="fa fa-check "></i> Register Rencana KPI</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-notification" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('assets/img/ic_warning.png') }}" class="img-warning-modal">
                    <h4 class="modal-title" id="myModalLabel">Detail Data KPI</h4>
                    Already updated!
                </div>
                <div class="text-right save-container">
                    <button type="button" class="btn btn-default btn-yellow">Ya</button>
                    <button type="button" class="btn btn-default btn-no" data-dismiss="modal">Tidak</button>
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
                    <button type="button" class="btn btn-default btn-yellow" id="callButton">Iya</button>
                    <button type="button" class="btn btn-default btn-no noCallButton" data-dismiss="modal">Tidak</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        document.getElementById('detailrencanaform').onsubmit=function () {
            $('.KodeRegistrasiKamus').val($('input[type=\'radio\'][name=\'KodeRegistrasiKamus\']:checked').val());
            $('#IDKodeAspekKPI').removeAttr('disabled');
            $('#IDJenisAppraisal').removeAttr('disabled');
            $('#IDPersentaseRealisasi').removeAttr('disabled');
            $('#IDSatuan').removeAttr('disabled');
            $('#Bobot').removeAttr('disabled');
            $('#s-option').removeAttr('disabled');
            $('#f-option').removeAttr('disabled');
        }
    </script>
    <script>
        $(function () {
            $('.double').keypress(
                function (evt) {
                    if (evt.which != 8 && evt.which != 0 && evt.which != 46 && evt.which < 48 || evt.which > 57) {
                        evt.preventDefault();
                    }
                });
        });

        function toggleIcon(e) {
            $(e.target)
                .prev('.panel-heading')
                .find(".more-less")
                .toggleClass('fa-angle-right fa-angle-down');
        }
        $('.panel-group').on('hidden.bs.collapse', toggleIcon);
        $('.panel-group').on('shown.bs.collapse', toggleIcon);

        @if($data['alldetail']->count() > 0)
        $('#rencanaunitkerja-table').DataTable({
            columnDefs: [{targets: [0, 1], orderable: false}],
            order: [],
            sDom: 'f',
            fnRowCallback: function (nRow, aData, iDisplayIndex) {
                var index = iDisplayIndex +1;
                $('td:eq(0)',nRow).html(index);
                return nRow;
            }
        });
        @endif

        $('#deleteItemKPI').click(function(event) {
            event.preventDefault();
            var checked = $(':checkbox:checked');
            var form = $('#modalform');
            var action = "{{ route('backends.kpi.rencana.individu.deleteItem') }}";
            var modalTitle = 'Hapus Item Rencana KPI Individu';
            var modalContent = 'Apakah anda yakin untuk menghapus item rencana KPI Anda?';
            form.append('<input type="hidden" name="_method" value="delete" class="appendedInput">');
            callAction(form, checked, action, modalTitle, modalContent);
        });

        $('.noCallButton').click(function () {
            $('#modalform').attr('action', '');
            $('.appendedInput').remove();
        });

        $('#register').click(function() {
            var form = $('#modalform');
            var action = "{{ route('backends.kpi.rencana.individu.register') }}";
            var modalTitle = 'Register Rencana KPI Individu';
            var modalContent = 'Apakah anda yakin untuk melakukan REGISTER Rencana KPI Individu Anda?';
            form.attr('action', action);
            form.append('<input type="hidden" name="id[]" value="'+ $(this).data('idrencana') + '" class="appendedInput">');
            $('#actionModalTitle').html(modalTitle);
            $('#actionModalContent').html(modalContent);
            $('#actionConfirmModal').modal('show');
            $('#callButton').click(function(event) {
                event.preventDefault();
                $(this).attr('disabled','disabled');
                $.ajax({
                    url: action,
                    type: 'post',
                    data: form.serialize().replace(/%5B%5D/g, '[]'),
                    success: function(result) {
                        if(result.status) {
                            window.location = "{{ route('backends.kpi.rencana.individu') }}";
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
                    event.preventDefault();
                });
            }
        }
        $('#updateItemKPI').click(function(event) {
            event.preventDefault();
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
                var url = "{{ route('backends.kpi.rencana.individu.unitkerja.edititem',':iditem') }}";
                window.location.href = url.replace(':iditem',id);
            }
        });
    </script>
@endsection
