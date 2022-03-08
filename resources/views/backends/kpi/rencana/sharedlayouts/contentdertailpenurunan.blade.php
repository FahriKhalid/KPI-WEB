<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 margin-top-30">
            <div class="panel panel-default panel-box panel-create">
                <div class="panel-body">
                    <div class="col-sm-12">
                        <div class="border-bottom-container margin-bottom-15">
                            @include('vendor.flash.message')
                            <div class="panel panel-primary">
                                <div class="panel-heading">Data Karyawan</div>
                                <div class="panel-body">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>Tahun</strong><span class="pull-right">{{ $data['header']->Tahun }}</span></li>
                                        <li class="list-group-item"><strong>NPK</strong><span class="pull-right">{{ $data['karyawan']->NPK }}</span></li>
                                        <li class="list-group-item"><strong>Nama Karyawan</strong><span class="pull-right">{{ $data['karyawan']->NamaKaryawan }}</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="panel-title-box no-border no-margin-bottom">Penurunan KPI</div>
                    </div>
                    <div class="table-responsive margin-min-15" style="overflow-x: scroll; width: calc(100% + 30px);">
                        <table id="cascading-table" class="table table-striped" width="2000px">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>KPI</th>
                                <th>Satuan</th>
                                <th>Bobot</th>
                                <th>NPK Bawahan</th>
                                <th>Jenis Cascade</th>
                                <th>Presentase KRA</th>
                                @for($i=1;$i<=$data['target'];$i++)
                                    <th>Target {{ $data['periodeTarget'] }} - {{ $i }}</th>
                                @endfor
                                <th>Keterangan</th>
                                <th>Created On</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @forelse($data['cascadeItems'] as $cascade)
                                {{-- <tr>
                                    <td></td>
                                    <td>{{ $cascade->detailkpi->DeskripsiKPI }}</td>
                                    <td>{{ $cascade->detailkpi->satuan->Satuan }}</td>
                                    <td>{{ $cascade->detailkpi->Bobot }}%</td>
                                    <td>{{ $cascade->NPKBawahan }} - {{ $cascade->NamaKaryawan }}</td>
                                    <td>{{ $cascade->detailkpi->jenisappraisal->JenisAppraisal }}</td>
                                    <td>{{ $cascade->PersentaseKRA }}%</td>
                                    @for($i=1;$i<=$data['target'];$i++)
                                        <td>{{ numberDisplay($cascade->detailkpi->{'Target'.$targetparser->targetCount($data['target'])[$i-1]}) }}</td>
                                    @endfor
                                    <td>{{ $cascade->Keterangan }}</td>
                                    <td>{{ $cascade->CreatedOn }}</td>
                                </tr> --}}
                                <tr> 
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $cascade->detailkpi->DeskripsiKPI }}</td>
                                    <td>{{ $cascade->detailkpi->satuan->Satuan }}</td> 
                                    {{-- <td>{{ empty($cascade->cascadedkpi) ? $cascade->cascadedkpi[0]->Bobot : $cascade->detailkpi->Bobot }}%</td> --}}
                                    <td>{{ count($cascade->cascadedkpi) > 0 ? $cascade->cascadedkpi[0]->Bobot : $cascade->detailkpi->Bobot }}</td>
                                    <td>{{ $cascade->NPKBawahan }} - {{ $cascade->NamaKaryawan }}</td>
                                    <td>{{ $cascade->detailkpi->jenisappraisal->JenisAppraisal }}</td>
                                    <td>{{ $cascade->PersentaseKRA }}%</td>
                                    @for($i=1;$i<=$data['target'];$i++)
                                        <td>{{ numberDisplay($cascade->{'Target'.$targetparser->targetCount($data['target'])[$i-1]}) }}</td>
                                    @endfor
                                    <td>{{ $cascade->Keterangan }}</td>
                                    <td>{{ $cascade->CreatedOn }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 9 + $data['target'] }}" class="text-center">Tidak ada data penurunan KPI</td>
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