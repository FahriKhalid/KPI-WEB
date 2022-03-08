<div class="col-sm-12">
    <div class="panel-group panel-faq" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading heading-main" role="tab" id="heading" style="background-color: #337ab7;">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse" aria-expanded="true" aria-controls="collapse">
                                                <span class="fa-stack" style="float: right;margin-top: -0.5%;">
                                                    <i class="fa fa-circle fa-inverse fa-stack-2x"></i>
                                                    <i class="more-less fa fa-stack-2x fa-angle-right"></i>
                                                </span>
                    <h4 class="panel-title" style="color: white;"><b>Data Detail</b></h4>
                </a>
            </div>
        </div>
        <div id="collapse" class="panel-collapse collapse row margin-bottom-15" role="tabpanel" aria-labelledby="heading">
            <div class="col-sm-6">
                <div class="panel panel-primary margin-top-30">
                    <div class="panel-heading" style="background-color: #337ab7">Data Karyawan</div>
                    <div class="panel-body">

                        @if(Auth::user()->IDRole == 8)
                            @php

                                $direksi = \DB::table("Ms_Direksi")->where("Npk", Auth::user()->NPK)->first();
                            @endphp

                            <ul class="list-group">
                            <li class="list-group-item"><b>Tahun</b><span class="pull-right">{{ $data['header']->Tahun }}</span></li>
                            <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['header']->NPK }}</span></li>
                            <li class="list-group-item"><b>Nama Karyawan</b><span class="pull-right">{{ $data['karyawan']->NamaKaryawan }}</span> </li>
                            <li class="list-group-item"><b>Grade</b><span class="pull-right">{{ $data['header']->Grade }}</span> </li>
                            <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ $direksi->Jabatan }}</span> </li>
                            <li class="list-group-item"><b>Kode Unit Kerja</b><span class="pull-right">{{ $direksi->KodeUnitKerja }}</span></li> 
                             
                        @else
                        <ul class="list-group">
                            <li class="list-group-item"><b>Tahun</b><span class="pull-right">{{ $data['header']->Tahun }}</span></li>
                            <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['header']->NPK }}</span></li>
                            <li class="list-group-item"><b>Nama Karyawan</b><span class="pull-right">{{ $data['karyawan']->NamaKaryawan }}</span> </li>
                            <li class="list-group-item"><b>Grade</b><span class="pull-right">{{ $data['header']->Grade }}</span> </li>
                            <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ $data['karyawan']->organization->position->PositionTitle }}</span> </li>
                            <li class="list-group-item"><b>Kode Unit Kerja</b><span class="pull-right">{{ $data['karyawan']->organization->position->KodeUnitKerja }}</span></li>
                            <li class="list-group-item"><b>Unit Kerja</b><span class="pull-right">{{ $data['karyawan']->organization->position->unitkerja->Deskripsi }}</span> </li>
                            <li class="list-group-item"><b>Canceled By</b><span class="pull-right">{{ (! empty($data['header']->CanceledBy))? $data['header']->CanceledBy : '-' }}</span> </li>
                            <li class="list-group-item"><b>Canceled On</b><span class="pull-right">{{ (! empty($data['header']->CanceledOn)) ? $data['header']->CanceledOn : '-' }}</span> </li>
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-primary margin-top-30">
                    <div class="panel-heading" style="background-color: #337ab7">Atasan Langsung</div>
                    <div class="panel-body">
                        <ul class="list-group">
                            <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['atasanLangsung'] != null ? $data['atasanLangsung']->NPK : '-'}}</span></li>
                            <li class="list-group-item"><b>Nama</b><span class="pull-right">{{ $data['atasanLangsung'] != null ? $data['atasanLangsung']->NamaKaryawan : '-'}}</span></li>
                            <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ $data['header']->JabatanAtasanLangsung != null ? $data['header']->JabatanAtasanLangsung : '-' }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-primary margin-top-15">
                    <div class="panel-heading" style="background-color: #337ab7">Atasan Tak Langsung</div>
                    <div class="panel-body">
                        <ul class="list-group">
                            <li class="list-group-item"><b>NPK</b><span class="pull-right">{{ $data['atasanTakLangsung'] != null ? $data['atasanTakLangsung']->NPK : '-'}}</span></li>
                            <li class="list-group-item"><b>Nama</b><span class="pull-right">{{ $data['atasanTakLangsung'] != null ? $data['atasanTakLangsung']->NamaKaryawan : '-'}}</span></li>
                            <li class="list-group-item"><b>Jabatan</b><span class="pull-right">{{ $data['header']->JabatanAtasanBerikutnya != null ? $data['header']->JabatanAtasanBerikutnya : '-'}}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-primary margin-top-30">
                    <div class="panel-heading" style="background-color: #337ab7">Catatan-Catatan</div>
                    <div class="panel-body">
                        <h4>Unconfirm</h4>
                        <p>{{ (! empty($data['header']->CatatanUnconfirm)) ? $data['header']->CatatanUnconfirm : '-' }}</p>
                        <hr>
                        <h4>Unapprove</h4>
                        <p>{{ (! empty($data['header']->CatatanUnapprove)) ? $data['header']->CatatanUnapprove : '-' }}</p>
                        <hr>
                        <h4>Canceled</h4>
                        <p>{{ (! empty($data['header']->AlasanCancel)) ? $data['header']->AlasanCancel : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>