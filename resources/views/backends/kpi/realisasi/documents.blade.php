@extends('layouts.app')

@section('submenu')
    @include('layouts.submenu.realisasi')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 margin-top-15">
                <ul class="kpi-menu">
                    <li>
                        <a href="{{ route('backends.kpi.realisasi.individu.editdetail', ['id' => $data['headerrealisasi']->ID]) }}">Realisasi KPI</a>
                    </li>
                    <li class="active">
                        <a href="{{ route('backends.kpi.realisasi.individu.indexdocument', ['id' => $data['headerrealisasi']->ID]) }}">Dokumen</a>
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
                                <div class="border-bottom-container margin-bottom-15">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">Data Karyawan</div>
                                        <div class="panel-body">
                                            <ul class="list-group">
                                                <li class="list-group-item"><strong>Tahun</strong><span class="pull-right">{{ $data['headerrealisasi']->Tahun }}</span></li>
                                                <li class="list-group-item"><strong>NPK</strong><span class="pull-right">{{ $data['karyawan']->NPK }}</span></li>
                                                <li class="list-group-item"><strong>Nama Karyawan</strong><span class="pull-right">{{ $data['karyawan']->NamaKaryawan }}</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="panel-title-box no-border">Dokumen Realisasi KPI</div>
                                @include('vendor.flash.message')
                            </div>
                            <div class="col-sm-11 col-sm-offset-1 margin-bottom-15">
                                <form class="form-horizontal" method="post" action="{{ route('backends.kpi.realisasi.individu.storeDocument', ['id' => $data['headerrealisasi']->ID]) }}" enctype="multipart/form-data">
                                    {!! csrf_field() !!}
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Caption</label>
                                        <div class="col-sm-7">
                                            <input type="text" name="Caption" class="form-control" placeholder="Caption" value="{{ old('Caption') }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Keterangan</label>
                                        <div class="col-sm-7">
                                            <input type="text" name="Keterangan" class="form-control" placeholder="Keterangan (opsional)" value="{{ old('Keterangan') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Pilih File</label>
                                        <div class="col-sm-7">
                                            <input id="uploadFile" class="form-control" placeholder="Choose File" disabled="disabled" />
                                            <div class="fileUpload btn btn-primary btn-blue">
                                                <span>Browse</span>
                                                <input id="uploadBtn" name="File" type="file" class="btn btn-default btn-blue upload" required/>
                                            </div>
                                            <div class="label-input">
                                                File Extension: pdf, doc, xls, jpg.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-7 save-container">
                                            <button type="submit" class="btn btn-default btn-blue">Tambah</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-12">
                                <div class="panel-title-box no-border no-margin-bottom">Daftar Dokumen Realisasi KPI</div>
                            </div>
                            <div class="col-sm-9">
                                <div class="custom-button-container">
                                    <a href="#">
                                        <button class="btn btn-link" id="deleteAttachmentRealisasi">
                                            <img src="{{ asset('assets/img/ic_add.png') }}" class="rotate-30"> Hapus
                                        </button>
                                    </a>
                                </div>
                            </div>
                            <div class="margin-min-15">
                                <table id="dokumenkpi-table" class="table table-striped" width="100%">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" id="checkAll"></th>
                                        <th>Caption</th>
                                        <th>Keterangan</th>
                                        <th>Extension</th>
                                        <th>Created On</th>
                                        <th class="text-center">Berkas</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($data['headerrealisasi']->attachments as $attachment)
                                        <tr>
                                            <td><input type="checkbox" name="id[]" value="{{ $attachment->ID }}"></td>
                                            <td>{{ $attachment->Caption }}</td>
                                            <td>{{ $attachment->Keterangan }}</td>
                                            <td>{{ $attachment->ContentType }}</td>
                                            <td>{{ $attachment->CreatedOn }}</td>
                                            <td class="text-center"><a href="{{ route('backends.kpi.realisasi.individu.documentdownload', ['id' => $data['headerrealisasi']->ID, 'attachmentID' => $attachment->ID]) }}" class="btn btn-info btn-sm"><i class="fa fa-download"></i> Unduh</a> </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada dokumen</td>
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
    <script type="text/javascript">
        document.getElementById("uploadBtn").onchange = function () {
            document.getElementById("uploadFile").value = this.value.split(/(\\|\/)/g).pop();
        };

        $('#deleteAttachmentRealisasi').click(function() {
            var form = $('#modalform');
            var checked = $(':checkbox:checked');
            var action = "{{ route('backends.kpi.realisasi.individu.deleteDocument') }}";
            var modalTitle = 'Hapus Lampiran Dokumen';
            var modalContent = 'Apakah anda yakin untuk menghapus dokumen terlampir pada Realisasi KPI Anda? Dokumen yang dihapus tidak akan dapat dikembalikan lagi.';
            form.append('<input type="hidden" name="_method" value="delete">');
            callAction(form, checked, action, modalTitle, modalContent);
        });

        $('.noCallButton').click(function () {
            $('#modalform').attr('action', '');
            $('.appendedInput').remove();
        });

        function callAction(form, checked, action, title, content) {
            if(checked.length === 0) {
                alert('Silakan pilih dokumen terlebih dahulu.');
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
                            alert('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText);
                        }
                    });
                    event.preventDefault();
                });
            }
        }
    </script>
@endsection
