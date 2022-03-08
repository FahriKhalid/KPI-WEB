<?php
namespace App\ApplicationServices\Dashboard;

use App\Domain\Karyawan\Services\PositionAbbreviation;
use App\Domain\User\Entities\User;

class DashboardBuilder
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Dashboard
     */
    protected $dashboard;

    /**
     * @var DashboardAdministrator
     */
    protected $dashboardAdministrator;

    /**
     * @var DashboardITSupport
     */
    protected $dashboardItSupport;

    /**
     * @var DashboardDeptKHI
     */
    protected $dashboardDeptKHI;

    /**
     * @var DashboardIngbangmen
     */
    protected $dashboardIngbangmen;

    /**
     * @var DashboardDiklat
     */
    protected $dashboardDiklat;

    protected $positionAbbreviation;

    /**
     * DashboardBuilder constructor.
     *
     * @param Dashboard $dashboard
     * @param DashboardAdministrator $dashboardAdministrator
     * @param DashboardITSupport $dashboardITSupport
     * @param DashboardDeptKHI $dashboardDeptKHI
     * @param DashboardDiklat $dashboardDiklat
     * @param DashboardIngbangmen $dashboardIngbangmen
     */
    public function __construct(
        Dashboard $dashboard,
        DashboardAdministrator $dashboardAdministrator,
        DashboardITSupport $dashboardITSupport,
        DashboardDeptKHI $dashboardDeptKHI,
        DashboardDiklat $dashboardDiklat,
        DashboardIngbangmen $dashboardIngbangmen,
        PositionAbbreviation $positionAbbreviation
    ) {
        $this->dashboard = $dashboard;
        $this->dashboardAdministrator = $dashboardAdministrator;
        $this->dashboardItSupport = $dashboardITSupport;
        $this->dashboardDeptKHI = $dashboardDeptKHI;
        $this->dashboardDiklat = $dashboardDiklat;
        $this->dashboardIngbangmen = $dashboardIngbangmen;
        $this->positionAbbreviation = $positionAbbreviation;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function user(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return array
     */
    public function build()
    { 
        $role = $this->user->UserRole->Role;
        
        $posAbbreviationCode = $this->user->karyawan->organization->position->PositionAbbreviation;  

        if($posAbbreviationCode == null){
            $posAbbreviationCode = \DB::table("Ms_Direksi")->where("Npk", \Auth::user()->NPK)->first();
            if($posAbbreviationCode){
                $posAbbreviationCode = $posAbbreviationCode->PositionAbbreviation;
            }
        }

        $codeShift = $this->user->karyawan->organization->Shift;
        $posAbbreviation = $this->positionAbbreviation->position($posAbbreviationCode)->codeShift($codeShift);
        switch ($role) {
            case "Administrator":
                return $this->dashboardAdministrator->applyInfoKPI()
                        ->applySummaryMaster()
                        ->applyPeriodeKPI()
                        ->applyChartSummaryKPI()
                        ->getData();
                break;
            case "IT Support":
                return $this->dashboardItSupport->applyInfoKPI()->applySummaryMaster()->getData();
                break;
            case "Dept. KHI":
                return $this->dashboardDeptKHI->applyInfoKPI()
                    ->applyPeriodeKPI()
                    ->applyChartSummaryKPI()
                    ->getData();
                break;
            case "Dept. Diklat & Managemen Pengetahuan (Diklat & MP)":
                return $this->dashboardDiklat->applyInfoKPI()->applyPeriodeKPI()->applyChartRencanaPengembanganSummary()
                        ->getData();
                break;
            case "Dept. Inovasi Pengembangan Managemen (Inbangmen)":
                return $this->dashboardIngbangmen->applyInfoKPI()
                    ->applySummaryMaster()
                    ->applyPeriodeKPI()
                    ->applyChartSummaryKPI()
                    ->getData();
                break;
            default:
                $builder = $this->dashboard->applyInfoKPI()->applySummaryDocumentWaiting($this->user);
                if ($posAbbreviation->isStructuralPosition()) {
                    $builder->applyChartSummaryKPIBawahan()->applyPeriodeKPI();
                }
                return $builder->getData();
                break;
        }
    }
}
