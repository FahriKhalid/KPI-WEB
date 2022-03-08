<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 08/23/2017
 * Time: 12:36 AM
 */

namespace App\Http\Controllers\Backends\KPI\Realisasi;

use ConsoleTVs\Charts\Facades\Charts;
use Illuminate\Http\Request;
use App\Http\Requests\KPI\Realisasi\GrafikNilaiRequest;

class GrafikNilaiController extends RealisasiIndividuController
{
    // /**
    //  * @param Request $request
    //  * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    //  */
    // public function index(Request $request)
    // {
    //     try {
    //         $data = $this->getSupportData($request);
    //         $data['_tahun'] = $this->periodeAktifRepository->datatable()->max('Tahun');
    //         $_=$this->realisasiKPIRepository->datatable($request->user())->where('Tahun', $data['_tahun'])->where('IsUnitKerja', 0)->orderby('KodePeriode');
    //         $data['_bawahan'] = '';
    //         $data['count'] = $_->get();
    //         $labels = $data['count']->pluck('periodeaktif.jenisperiode.NamaPeriodeKPI');
    //         $values = $data['count']->pluck('NilaiAkhir');
    //         $data['chart'] = Charts::create('line', 'chartjs')
    //             ->title('Data Nilai KPI Individu '.(!empty($data['count']->first()->Tahun)?$data['count']->first()->Tahun:null))
    //             ->elementLabel('Nilai KPI')
    //             ->labels(!empty($labels)?$labels:[])
    //             ->values(!empty($values)?$values:[])
    //             ->dimensions(0, 150)
    //             ->backgroundcolor('transparent')
    //             ->xaxistitle('Periode')
    //             ->yaxistitle('Nilai KPI')
    //             ->colors(['#ffa500'])
    //             ->loader(false)
    //             ->loadercolor('#ffa500')
    //             ->responsive(false);
    //         if (count($data['count'])==0) {
    //             flash('Tidak ada data yang ditampilkan', 'info')->info();
    //         }

    //         return view('backends.kpi.realisasi.grafiknilai.index', compact('data'));
    //     } catch (\ErrorException $errorException) {
    //         flash()->error('Galat :'.$errorException->getMessage())->important();
    //         return view('backends.kpi.realisasi.grafiknilai.index', compact('data'));
    //     }
    // }

    // /**
    //  * @param GrafikNilaiRequest $request
    //  * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    //  */
    // public function find(GrafikNilaiRequest $request)
    // {
    //     try {
    //         $data = $this->getSupportData($request);
    //         if (!$request->IsBawahan) {
    //             $_ = $this->realisasiKPIRepository->datatable($request->user(), $request->IsUnitKerja);
    //         } else {
    //             $user = $this->karyawanRepository->findByNPK($request->NPK)->user;
    //             $_ = $this->realisasiKPIRepository->datatable($user, $request->IsUnitKerja);
    //         };
    //         $data['count']=$_->where('Tahun', $request->Tahun)->orderby('KodePeriode')->get();
    //         $labels = $data['count']->pluck('periodeaktif.jenisperiode.NamaPeriodeKPI');
    //         $values = $data['count']->pluck('NilaiAkhir');
    //         $data['isbawahan'] = $request->get('IsBawahan');
    //         $data['_bawahan'] = $request->get('NPK');
    //         $data['_IsUnitKerja'] = $request->get('IsUnitKerja');
    //         $data['_tahun'] = $request->get('Tahun');

    //         $data['chart'] = Charts::create('line', 'chartjs')
    //             ->title('Data Nilai KPI Individu '.(!empty($data['count']->first()->Tahun)?$data['count']->first()->Tahun:$data['_tahun']))
    //             ->elementLabel('Nilai KPI')
    //             ->labels(!empty($labels)?$labels:[])
    //             ->values(!empty($values)?$values:[])
    //             ->dimensions(0, 150)
    //             ->backgroundcolor('transparent')
    //             ->loader(false)
    //             ->loadercolor('#ffa500')
    //             ->responsive(false);
    //         if (count($data['count'])==0) {
    //             flash('Tidak ada data yang ditampilkan', 'info')->info();
    //         }
    //         return view('backends.kpi.realisasi.grafiknilai.index', compact('data'));
    //     } catch (\ErrorException $errorException) {
    //         flash()->error('Galat :'.$errorException->getMessage())->important();
    //         return view('backends.kpi.realisasi.grafiknilai.index', compact('data'));
    //     }
    // }

    // /**
    //  * @param Request $request
    //  * @return mixed
    //  */
    // public function getSupportData(Request $request)
    // {
    //     $data['bawahan'] = $request->user()->abbreviation() !=null ? $this->karyawanRepository->findBawahan(
    //         $request->user()->karyawan->organization->position->PositionAbbreviation,
    //         $request->user()->abbreviation()->getParentPosition()
    //     )->pluck('NamaKaryawan', 'NPK')->map(function ($item, $key) {
    //         return "$key - $item";
    //     })->toArray():[];
    //     $data['tahun']=$this->periodeAktifRepository->datatable()->pluck('Tahun', 'Tahun')->toArray();
    //     $data['unitkerja']=$request->user()->karyawan->organization->position->unitkerja->Deskripsi;
    //     return $data;
    // }
}
