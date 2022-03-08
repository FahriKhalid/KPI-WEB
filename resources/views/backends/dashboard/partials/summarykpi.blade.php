<div class="container">
    <div class="row">
        <div class="col-md-6 col-sm-6">
            <h4 class="blue-color">
                Rencana KPI
                <div class="col-md-3 pull-right">
                    <select id="periodeRencana" class="form-control">
                        @foreach($data['periodeYears'] as $year)
                            <option value="{{ $year->Tahun }}">{{ $year->Tahun }}</option>
                        @endforeach
                    </select>
                </div>
            </h4>
            <div class="panel panel-body margin-top-30">
                <div id="canvas-holder" style="width:60%">
                    <canvas id="chart-area-rencana" />
                </div>
            </div>
        </div>
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
    </div>
</div>