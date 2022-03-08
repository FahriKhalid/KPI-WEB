<script type="text/javascript">
    function loadDataRencanaSummary(params) {
        var dataRencanaSummary = [0,0,0,0];

        var configRencana = {
            type: 'pie',
            data: {
                datasets: [{
                    data: dataRencanaSummary,
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
            url: "{{ route('dashboard.api.rencana.summary') }}",
            data: params,
            success: function (data) {
                $.each(data.summaryRencanaKPI, function (key, value) {
                    if (key == 'draft') {
                        dataRencanaSummary[0] = value;
                    } else if(key == 'registered') {
                        dataRencanaSummary[1] = value;
                    } else if(key == 'confirmed') {
                        dataRencanaSummary[2] = value;
                    } else {
                        dataRencanaSummary[3] = value;
                    }
                });
                var ctx = document.getElementById("chart-area-rencana").getContext("2d");
                window.myDoughnut = new Chart(ctx, configRencana);
                $('#periodeRencana').val(data.periodeYear)
            }
        });
    }

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

    $('#periodeRencana').change(function(event) {
        event.preventDefault();
        loadDataRencanaSummary({periodeTahunRencana: $(this).val()});
    });

    $('#periodeYear').change(function(event) {
        event.preventDefault();
        loadDataRealisasiSummary({periodeTahunRealisasi: $('#periodeYear').val(), periodeRealisasi: $('#periodeRealisasi').val()});
    });

    $('#periodeRealisasi').change(function(event) {
        event.preventDefault();
        loadDataRealisasiSummary({periodeTahunRealisasi: $('#periodeYear').val(), periodeRealisasi: $('#periodeRealisasi').val()});
    });

    window.onload = function() {
        loadDataRencanaSummary({});
        loadDataRealisasiSummary({});
    };
</script>