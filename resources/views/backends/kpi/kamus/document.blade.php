@include('vendor.loader.loader',['phase'=>''])
@extends('vendor.loader.loader',['phase'=>'Memuat antarmuka import excel ...'])
<div class="modal fade modal-notification" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="panel-title-box no-border">Upload dengan file excel</div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <hr>
                <form action="{{route('backend.kpi.kamus.document.store')}}" method="post" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                <div class="modal-body text-center">
                        <div class="form-group">
                            <p class="alert alert-danger "><b>Peringatan !</b> Pastikan menggunakan <a href="{{ asset('repository/template/KamusKPI.xlsx') }}">Template excel ini</a></p>
                            <label class="col-sm-2 control-label hidden-xs">Pilih File</label>
                            <div class="col-sm-5">
                                <input name="Excel" id="uploadFile" class="form-control" placeholder="Choose File" readonly />
                            </div>
                            <div class="col-sm-5">
                                <div class="fileUpload btn btn-primary btn-blue">
                                    <span>Browse</span>
                                    <input id="uploadBtn" name="Excel" type="file" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" class="btn btn-default btn-orange upload"/>
                                </div>
                            </div>
                            <div class="col-sm-10 col-sm-offset-2">
                                <div class="label-input text-left">
                                    File Ekstensi: *.xls, *.xlsx
                                </div>
                            </div>
                            <br>
                        </div>
                </div>
                    <hr>
                <div class="text-right save-container">
                    <button type="button" class="btn btn-default btn-yellow" data-dismiss="modal">Batal</button>
                    <button type="button submit" class="btn btn-default btn-blue" style="margin-right: 10px;">Unggah</button>
                </div>
                    <br>
                </form>
            </div>
    </div>
</div>
<script>
    function modalUpload(){
        $.ajax({success:function(){
            $('#uploadModal').modal('show')
        }});
    }
    document.getElementById("uploadBtn").onchange = function (e) {
        var fileExtension = this.value.match(/\.([^\.]+)$/)[1];
        switch(fileExtension){
            case 'xls':
                //alert('allowed');
                break;
            case 'xlsx':
                //alert('allowed');
                break;
            default:
                alert('Peringatan! File yang diupload bukan ekstensi excel (*.xls, *.xlsx)');
                this.value='';
        }
        document.getElementById("uploadFile").value = this.value.split(/(\\|\/)/g).pop();
    };
</script>