<?php

namespace App\ApplicationServices\Dashboard;

use App\Domain\Karyawan\Entities\MasterPosition;
use App\Domain\KPI\Entities\JenisPeriode;
use App\Domain\User\Entities\User;
use App\Infrastructures\Repositories\Karyawan\KaryawanRepository;
use App\Infrastructures\Repositories\KPI\InfoRepository;
use App\Infrastructures\Repositories\KPI\PeriodeAktifRepository;
use App\Infrastructures\Repositories\KPI\RealisasiKPIRepository;
use App\Infrastructures\Repositories\KPI\RencanaKPIRepository;
use App\Infrastructures\Repositories\KPI\UnitKerjaRepository;

class Dashboard
{
    /**
     * @var InfoRepository
     */
    protected $infoKPIRepository;

    /**
     * @var RencanaKPIRepository
     */
    protected $rencanaKPIRepository;

    /**
     * @var RealisasiKPIRepository
     */
    protected $realisasiKPIRepository;

    /**
     * @var KaryawanRepository
     */
    protected $karyawanRepository;

    /**
     * @var UnitKerjaRepository
     */
    protected $unitKerjaRepository;

    /**
     * @var PeriodeAktifRepository
     */
    protected $periodeAktifRepository;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $baseView = 'backends.dashboard.';

    /**
     * @var string
     */
    protected $view = 'index';

    /**
     * Dashboard constructor.
     *
     * @param InfoRepository $infoKPI
     * @param RencanaKPIRepository $rencanaKPIRepository
     * @param RealisasiKPIRepository $realisasiKPIRepository
     * @param KaryawanRepository $karyawanRepository
     * @param UnitKerjaRepository $unitKerjaRepository
     * @param PeriodeAktifRepository $periodeAktifRepository
     */
    public function __construct(
        InfoRepository $infoKPI,
        RencanaKPIRepository $rencanaKPIRepository,
        RealisasiKPIRepository $realisasiKPIRepository,
        KaryawanRepository $karyawanRepository,
        UnitKerjaRepository $unitKerjaRepository,
        PeriodeAktifRepository $periodeAktifRepository
    ) {
        $this->infoKPIRepository = $infoKPI;
        $this->rencanaKPIRepository = $rencanaKPIRepository;
        $this->realisasiKPIRepository = $realisasiKPIRepository;
        $this->karyawanRepository = $karyawanRepository;
        $this->unitKerjaRepository = $unitKerjaRepository;
        $this->periodeAktifRepository = $periodeAktifRepository;
    }

    /**
     * @return $this
     */
    public function applyInfoKPI()
    {
        $this->data['infoKPI'] = $this->infoKPIRepository->allActive(date('Y-m-d H:i:s'));
        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function applySummaryDocumentWaiting(User $user)
    {
        $this->data['pendingConfirmedCount'] = $this->rencanaKPIRepository->countPendingDocument($user, 2);
        $this->data['pendingApprovedCount'] = $this->rencanaKPIRepository->countPendingDocument($user, 3);
        $this->data['pendingConfirmedRealisasiCount'] = $this->realisasiKPIRepository->countWaitingDocumentByAtasan($user->NPK);
        $this->data['pendingApprovedRealisasiCount'] = $this->realisasiKPIRepository->countWaitingDocumentByAtasan($user->NPK, 'taklangsung');
        return $this;
    }

    /**
     * @return $this
     */
    public function applySummaryMaster()
    {
        $this->data['totalKaryawan'] = $this->karyawanRepository->count();
        $this->data['totalUnitkerja'] = $this->unitKerjaRepository->count();
        $this->data['totalPosisi'] = MasterPosition::count();
        return $this;
    }

    /**
     * @return $this
     */
    public function applyPeriodeKPI()
    {
        $this->data['periodeYears'] = $this->periodeAktifRepository->getAllPeriode();
        $this->data['periodeRealisasi'] = JenisPeriode::select('ID', 'KodePeriode', 'NamaPeriodeKPI')->get();
        return $this;
    }

    /**
     * @return $this
     */
    public function applyChartSummaryKPI()
    {
        $this->data['chartSummaryKPI'] = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function applyChartRencanaPengembanganSummary()
    {
        $this->data['chartSummaryRencanaPengembangan'] = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function applyChartSummaryKPIBawahan()
    {
        $this->data['chartSummaryKPIBawahan'] = true;
        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'data' => $this->data,
            'view' => $this->baseView.$this->view
        ];
    }
}
