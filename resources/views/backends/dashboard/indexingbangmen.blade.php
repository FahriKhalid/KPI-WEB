@extends('layouts.app')

@section('content')
    <div class="container margin-bottom-15">
        <div class="row">
            <div class="col-md-6 col-sm-6 info-text-hi">
                <h4 class="blue-color">Selamat Datang, {{ title_case(auth()->user()->Karyawan->NamaKaryawan) }} ({{ title_case(auth()->user()->UserRole->Role) }})</h4>
            </div>
            <div class="col-md-6 col-sm-6 info-text-hi text-right">
                <div class="date realtime-date"></div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <h4 class="blue-color">Realisasi KPI
                    <div class="col-md-3 pull-right">
                        <select id="periodeYear" class="form-control">
                            @foreach($data['periodeYears'] as $year)
                                <option value="{{ $year->Tahun }}">{{ $year->Tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 pull-right">
                        <select id="periodeRealisasi" class="form-control">
                            @foreach($data['periodeRealisasi'] as $periodeRealisasi)
                                <option value="{{ $periodeRealisasi->ID }}">{{ $periodeRealisasi->NamaPeriodeKPI }}</option>
                            @endforeach
                        </select>
                    </div>
                </h4>
                <div class="panel panel-body margin-top-30">
                    <div id="canvas-holder" style="width:60%">
                        <canvas id="chart-area-realisasi" />
                    </div>
                </div>
            </div>
            <div class="col-md-6 margin-top-30">
                <div class="col-md-12 col-sm-12">
                    <div class="alert alert-approved">
                        <h4 class="title-alert">Total Unit Kerja</h4>
                        <h2 class="content-alert">{{ $data['totalUnitkerja'] }}</h2>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12">
                    <div class="alert alert-approved">
                        <h4 class="title-alert">Total Karyawan</h4>
                        <h2 class="content-alert">{{ $data['totalKaryawan'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h5 class="info-title-in-dashboard">Info KPI Online</h5>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 text-right view-more">
                        <a href="javascript:void(0)" onclick="selebihnya()">
                            Lihat Lebih
                        </a>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="row">
                    <?php $i=0; ?>
                    @forelse($data['infoKPI'] as $item)
                        <div class="col-sm-3 col-xs-6">
                            <div class="item-listing" id="{{$i}}" style="height: 300px;">
                                <a href="{{ route('dashboard.infokpi.detail', $item->ID) }}">
                                    @if(isset($item->Gambar))
                                        <img src="{{ route('image.resize', ['modulename' => 'info', 'width' => 960, 'height' => 637, 'imagename' => $item->Gambar]) }}" class="responsive-image">
                                    @else
                                        <img src="{{ route('image.resize', ['modulename' => 'dummy', 'width' => 960, 'height' => 637, 'imagename' => 'dummy.png']) }}" class="responsive-image">
                                    @endif
                                    <div class="description-listing">
                                        <h4>{{substr(strip_tags($item->Judul,''),0,25) }} {{strlen($item->Judul)>25? '...': ''}}</h4>
                                        <div class="date">
                                            {{ date('d M Y', strtotime($item->Tanggal_publish)) }} s/d {{ (! is_null($item->Tanggal_berakhir)) ? date('d M Y', strtotime($item->Tanggal_berakhir)) : '-' }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php $i+=1; ?>
                        </div>
                    @empty
                        <div class="col-sm-12 col-xs-12">
                            <div class="empty-info text-center">
                                <img src="{{ asset('assets/img/ic_info_empty.png') }}" class="responsive-image">
                                <h3 class="no-info-text">Tidak ada informasi</h3>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script type="text/javascript" src="{{ asset('assets/js/Chart.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script type="text/javascript">
        var datetime_moment = null,
            date_moment = null;

        var update = function () {
            date_moment = moment(new Date())
            datetime_moment.html(date_moment.format('DD MMMM YYYY, h:mm:ss a'));
        };

        $(document).ready(function(){
            datetime_moment = $('.realtime-date');
            update();
            setInterval(update, 1000);
        });
    </script>
    <script type="text/javascript">
        function loadDataRealisasiSummary(params)
        {
            var dataRealisasiSummary = [0,0,0,0];

            var configRealisasi = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: dataRealisasiSummary,
                        backgroundColor: [
                            window.chartColors.red,
                            window.chartColors.orange,
                            window.chartColors.yellow,
                            window.chartColors.green
                        ],
                        label: 'Dataset 1'
                    }],
                    labels: [
                        "Draft",
                        "Registered",
                        "Confirmed",
                        "Approved"
                    ]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'left'
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            };

            $.ajax({
                url: "{{ route('dashboard.api.realisasi.summary') }}",
                data: params,
                success: function (data) {
                    $.each(data.summaryRealisasiKPI, function (key, value) {
                        if (key == 'draft') {
                            dataRealisasiSummary[0] = value;
                        } else if(key == 'registered') {
                            dataRealisasiSummary[1] = value;
                        } else if(key == 'confirmed') {
                            dataRealisasiSummary[2] = value;
                        } else {
                            dataRealisasiSummary[3] = value;
                        }
                    });
                    var ctxRalisasi = document.getElementById("chart-area-realisasi").getContext("2d");
                    window.myDoughnut = new Chart(ctxRalisasi, configRealisasi);
                    $('#periodeYear').val(data.periodeYear);
                    $('#periodeRealisasi').val(data.periodeRealisasi);
                }
            });
        }

        $('#periodeYear').change(function(event) {
            event.preventDefault();
            loadDataRealisasiSummary({periodeTahunRealisasi: $('#periodeYear').val(), periodeRealisasi: $('#periodeRealisasi').val()});
        });

        $('#periodeRealisasi').change(function(event) {
            event.preventDefault();
            loadDataRealisasiSummary({periodeTahunRealisasi: $('#periodeYear').val(), periodeRealisasi: $('#periodeRealisasi').val()});
        });

        window.onload = function() {
            loadDataRealisasiSummary({});
        };
    </script>
@endsection