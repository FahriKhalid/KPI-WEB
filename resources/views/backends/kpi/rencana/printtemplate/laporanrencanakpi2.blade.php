@inject('targetparser', 'Pkt\Domain\Rencana\Services\TargetParserService')
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" media="all">
    <style media="screen">
        /*body {*/
        /*display: none;*/
        /*}*/
    </style>
    <style media="print">
        body {
            display: block;
        }
    </style>
    <style>
        .col-print-1 {
            width: 8%;
            float: left;
        }

        .col-print-2 {
            width: 16%;
            float: left;
        }

        .col-print-3 {
            width: 25%;
            float: left;
        }

        .col-print-4 {
            width: 33%;
            float: left;
        }

        .col-print-5 {
            width: 42%;
            float: left;
        }

        .col-print-6 {
            width: 50%;
            float: left;
        }

        .col-print-7 {
            width: 58%;
            float: left;
        }

        .col-print-8 {
            width: 66%;
            float: left;
        }

        .col-print-9 {
            width: 75%;
            float: left;
        }

        .col-print-10 {
            width: 83%;
            float: left;
        }

        .col-print-11 {
            width: 92%;
            float: left;
        }

        .col-print-12 {
            width: 100%;
            float: left;
        }

        td {
            border-collapse: collapse;
            border: 1px black solid;
        }

        tr:nth-of-type(5) td:nth-of-type(1) {
            visibility: hidden;
        }

        .rotate {
            /* FF3.5+ */
            -moz-transform: rotate(-90.0deg);
            /* Opera 10.5 */
            -o-transform: rotate(-90.0deg);
            /* Saf3.1+, Chrome */
            -webkit-transform: rotate(-90.0deg);
            /* IE6,IE7 */
            filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);
            /* IE8 */
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)";
            /* Standard */
            transform: rotate(-90.0deg);
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <title>Pupuk Kaltim - Laporan Rencana KPI {{$data['header']->IsKPIUnitKerja?'Unit Kerja':'Individu'}}</title>
</head>
<body>
<htmlpageheader name="page-header">
    <h3>{{-- <img alt="Key Performance Indicator Logo" src="{{ asset('assets/img/logo_inlanding.png') }}" style=" height: 150%;">--}}
        PT Pupuk Kalimantan Timur - KPI Online</h3>
</htmlpageheader>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 margin-top-30">
            <div class="panel panel-default panel-box panel-create">
                <div class="panel-body">
                    <table width="100%">
                        <tr>
                            <td bgcolor="grey" color="white">Data Detail Rencana KPI</td>
                            <td align="right" bgcolor="black"
                                color="white">{{ strtoupper($data['header']->statusdokumen->StatusDokumen) }}</td>
                        </tr>
                    </table>
                    <div class="col-sm-6">
                        <div style="border-bottom: solid 1px;margin-top: 3px;">Data Karyawan</div>
                        <div class="panel-body">
                            <table width="100%">
                                <tr>
                                    <td width="20%" align="right"><b>Tahun</b></td>
                                    <td>{{ $data['header']->Tahun }}</td>
                                </tr>
                                <tr>
                                    <td width="20%" align="right"><b>NPK</b></td>
                                    <td>{{ $data['header']->NPK }}</td>
                                </tr>
                                <tr>
                                    <td width="20%" align="right"><b>Nama Karyawan</b></td>
                                    <td>{{ $data['karyawan']->NamaKaryawan }}</td>
                                </tr>
                                <tr>
                                    <td width="20%" align="right"><b>Grade</b></td>
                                    <td>{{ $data['header']->Grade }}</td>
                                </tr>
                                <tr>
                                    <td width="20%" align="right"><b>Jabatan</b></td>
                                    <td>{{ $data['karyawan']->organization->position->PositionTitle }}</td>
                                </tr>
                                <tr>
                                    <td width="20%" align="right"><b>Kode Unit Kerja</b></td>
                                    <td>{{ $data['karyawan']->organization->position->KodeUnitKerja }}</td>
                                </tr>
                                <tr>
                                    <td width="20%" align="right"><b>Unit Kerja</b></td>
                                    <td>{{ $data['karyawan']->organization->position->unitkerja->Deskripsi }}</td>
                                </tr>
                                <tr>
                                    <td width="20%" align="right"><b>Created By</b></td>
                                    <td>{{ $data['header']->NPK }}</td>
                                </tr>
                                <tr>
                                    <td width="20%" align="right"><b>Created On</b></td>
                                    <td>{{ $data['header']->CreatedOn }}</td>
                                </tr>
                                <tr>
                                    <td width="20%" align="right"><b>Registered By</b></td>
                                    <td>{{ (! empty($data['header']->RegisteredBy))? $data['header']->RegisteredBy : '-' }}</td>
                                </tr>
                                <tr>
                                    <td width="20%" align="right"><b>Registered On</b></td>
                                    <td>{{ (! empty($data['header']->RegisteredOn)) ? $data['header']->RegisteredOn : '-' }}</td>
                                </tr>
                                <tr>
                                    <td width="20%" align="right"><b>Confirmed By</b></td>
                                    <td>{{ (! empty($data['header']->ConfirmedBy))? $data['header']->ConfirmedOn : '-' }}</td>
                                </tr>
                                <tr>
                                    <td width="20%" align="right"><b>Confirmed On</b></td>
                                    <td>{{ (! empty($data['header']->ConfirmedOn)) ? $data['header']->ConfirmedBy : '-' }}</td>
                                </tr>
                                <tr>
                                    <td width="20%" align="right"><b>Approved By</b></td>
                                    <td>{{ (! empty($data['header']->ApprovedBy))? $data['header']->ApprovedBy : '-' }}</td>
                                </tr>
                                <tr>
                                    <td width="20%" align="right"><b>Approved On</b></td>
                                    <td>{{ (! empty($data['header']->ApprovedOn)) ? $data['header']->ApprovedOn : '-' }}</td>
                                </tr>
                                <tr>
                                    <td width="20%" align="right"><b>Canceled By</b></td>
                                    <td>{{ (! empty($data['header']->CanceledBy))? $data['header']->CanceledBy : '-' }}</td>
                                </tr>
                                <tr>
                                    <td width="20%" align="right"><b>Canceled On</b></td>
                                    <td>{{ (! empty($data['header']->CanceledOn)) ? $data['header']->CanceledOn : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        {{--</div>--}}
                    </div>
                    <div class="col-sm-6">
                        <div style="border-bottom: solid 1px;margin-top: 3px;">Atasan Langsung</div>
                        <table width="100%">
                            <tr>
                                <td width="20%" align="right"><b>NPK</b></td>
                                <td>{{ $data['atasanLangsung']->NPK }}</td>
                            </tr>
                            <tr>
                                <td width="20%" align="right"><b>Nama</b></td>
                                <td>{{ $data['atasanLangsung']->NamaKaryawan }}</td>
                            </tr>
                            <tr>
                                <td width="20%" align="right"><b>Jabatan</b></td>
                                <td>{{ $data['header']->JabatanAtasanLangsung }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-6">
                        <div style="border-bottom: solid 1px;margin-top: 3px;">Atasan Tak Langsung</div>
                        <table width="100%">
                            <tr>
                                <td width="20%" align="right"><b>NPK</b></td>
                                <td>{{ $data['atasanTakLangsung']->NPK }}</td>
                            </tr>
                            <tr>
                                <td width="20%" align="right"><b>Nama</b></td>
                                <td>{{ $data['atasanTakLangsung']->NamaKaryawan }}</td>
                            </tr>
                            <tr>
                                <td width="20%" align="right"><b>Jabatan</b></td>
                                <td>{{ $data['header']->JabatanAtasanBerikutnya }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-primary">
                            <div style="border-bottom: solid 1px;margin-top: 3px;">Catatan-Catatan</div>
                            <div class="panel-body">
                                <h4>Unconfirm</h4>
                                <p>{{ (! empty($data['header']->CatatanUnconfirm)) ? $data['header']->CatatanUnconfirm : '-' }}</p>
                                <h4>Unapprove</h4>
                                <p>{{ (! empty($data['header']->CatatanUnapprove)) ? $data['header']->CatatanUnapprove : '-' }}</p>
                                <h4>Canceled</h4>
                                <p>{{ (! empty($data['header']->AlasanCancel)) ? $data['header']->AlasanCancel : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <pagebreak>
                <div class="panel panel-default panel-box panel-create">
                    <div class="panel-body">
                        <table width="100%">
                            <tr>
                                <td bgcolor="grey" color="white">Item KPI yang Diturunkan</td>
                            </tr>
                        </table>
                        <div class="margin-min-15">
                            @php
                                $bobotCascade = 0;
                            @endphp
                            <div class="table-responsive">
                                <table id="table-data" class="table table-striped" rotate="-90" width="100%"
                                       style="margin-left: 10px;">
                                    <thead>
                                    <tr style="background-color:black ;border: solid white 2px;">
                                        <th color="white">KPI</th>
                                        <th color="white">Satuan</th>
                                        <th color="white">Bobot</th>
                                        @for($i=1;$i<=$data['target'];$i++)
                                            <th color="white">Target {{ $data['periodeTarget'] }} - {{ $i }}</th>
                                        @endfor
                                        <th color="white">Jenis Cascade</th>
                                        <th color="white">Presentase KRA</th>
                                        <th color="white">Keterangan</th>
                                        <th color="white">Created By</th>
                                        <th color="white">Created On</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($data['cascadeItems'] as $cascade)
                                        @if(! $cascade->IsApproved)
                                            @php
                                                $bobotCascade += $cascade->detailkpi->Bobot
                                            @endphp
                                        @endif
                                        <tr>
                                            <td style="border-bottom: 1px solid;">{{ $cascade->detailkpi->DeskripsiKPI }}</td>
                                            <td style="border-bottom: 1px solid;">{{ $cascade->detailkpi->satuan->Satuan }}</td>
                                            <td style="border-bottom: 1px solid;">{{ $cascade->detailkpi->Bobot }}%</td>
                                            @for($i=1;$i<=$data['target'];$i++)
                                                <td style="border-bottom: 1px solid;">{{ $cascade->detailkpi->{'Target'.$targetparser->targetCount($data['target'])[$i-1]} }}</td>
                                            @endfor
                                            <td style="border-bottom: 1px solid;">-</td>
                                            <td style="border-bottom: 1px solid;">{{ $cascade->PersentaseKRA }}%</td>
                                            <td style="border-bottom: 1px solid;">{{ $cascade->Keterangan }}</td>
                                            <td style="border-bottom: 1px solid;">{{ $cascade->CreatedBy }}</td>
                                            <td style="border-bottom: 1px solid;">{{ $cascade->CreatedOn }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ 8 + $data['target'] }}" class="text-center">Tidak ada data
                                                penurunan KPI
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </pagebreak>
            <pagebreak>
                <div class="panel panel-default panel-box panel-create">
                    <div class="panel-body">
                        <table width="100%">
                            <tr>
                                <td bgcolor="grey" color="white">Data Detail Rencana KPI</td>
                                <td align="right" bgcolor="black"
                                    color="white">{{ strtoupper($data['header']->statusdokumen->StatusDokumen) }}</td>
                            </tr>
                        </table>
                        <div class="margin-min-15">
                            <table class="table table-striped" width="100%" rotate="-90" style="margin-left: 10px;">
                                <thead>
                                <tr style="background-color:black ;border: solid white 2px;">
                                    <th color="white">Kelompok</th>
                                    <th color="white">Sifat</th>
                                    <th color="white">Presentase Realisasi</th>
                                    <th color="white">KRA</th>
                                    <th color="white">KPI</th>
                                    <th color="white">Satuan</th>
                                    <th color="white">Bobot</th>
                                    <th color="white">Sbg KPI Bawahan</th>
                                    @for($i=1; $i<=$data['target']; $i++)
                                        <th color="white">Target {{$data['periodeTarget']}} - {{ $i }}</th>
                                    @endfor
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $totalBobot = 0;
                                @endphp
                                @forelse($data['alldetail'] as $detail)
                                    <tr>
                                        <td style="border-bottom: 1px solid;">{{ $detail->aspekkpi->AspekKPI }}</td>
                                        <td style="border-bottom: 1px solid;">{{ $detail->jenisappraisal->JenisAppraisal }}</td>
                                        <td style="border-bottom: 1px solid;">{{ $detail->persentaserealisasi->PersentaseRealisasi }}</td>
                                        <td style="border-bottom: 1px solid;">{{ $detail->DeskripsiKRA }}</td>
                                        <td style="border-bottom: 1px solid;">{{ $detail->DeskripsiKPI }}</td>
                                        <td style="border-bottom: 1px solid;">{{ $detail->satuan->Satuan }}</td>
                                        <td style="border-bottom: 1px solid;">{{ $detail->Bobot }}%</td>
                                        <td style="border-bottom: 1px solid;">{{ ($detail->IsKRABawahan) ? 'Y' : 'N' }}</td>
                                        @for($i=1; $i<=$data['target']; $i++)
                                            <td style="border-bottom: 1px solid;">{{ $detail->{'Target'.$targetparser->targetCount($data['target'])[$i - 1]} }}</td>
                                        @endfor

                                        @php
                                            $totalBobot += $detail->Bobot;
                                        @endphp
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 10 + $data['target'] }}" class="text-center">Tidak ada data
                                            detail rencana KPI
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="8">Total Item KPI</th>
                                    <th>{{ $data['alldetail']->count() }}</th>
                                </tr>
                                <tr>
                                    <th colspan="8">Total Bobot</th>
                                    <th>{{ $totalBobot }}%</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </pagebreak>
        </div>
    </div>
</div>
<htmlpagefooter name="page-footer">
    <hr>
    <small>
        <table width="100%">
            <tr>
                <td width="40%">PT Pupuk Kalimantan Timur</td>
                <td width="20%" align="center">- {PAGENO} -</td>
                <td width="40%" align="right">Rencana Individu KPI</td>
            </tr>
        </table>
    </small>
</htmlpagefooter>
</body>
<script>
    $(document).ready(function () {
        $('.rotate').css('height', $('.rotate').width());
    });
</script>