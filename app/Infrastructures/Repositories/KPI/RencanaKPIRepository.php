<?php
namespace App\Infrastructures\Repositories\KPI;

use Illuminate\Support\Facades\DB;
use App\Domain\Realisasi\Entities\HeaderRealisasiKPI;
use App\Domain\Rencana\Entities\Attachment;
use App\Domain\Rencana\Entities\DetilRencanaKPI;
use App\Domain\Rencana\Entities\HeaderRencanaKPI;
use App\Domain\Rencana\Entities\KRAKPIDetail;
use App\Exceptions\DomainException;

class RencanaKPIRepository
{
    /**
     * @var HeaderRencanaKPI
     */
    protected $header;

    /**
     * @var DetilRencanaKPI
     */
    protected $detail;

    /**
     * @var Attachment
     */
    protected $attachment;

    /**
     * @var KRAKPIDetail
     */
    protected $penurunanKPI;

    /**
     * RencanaKPIRepository constructor.
     *
     * @param HeaderRencanaKPI $header
     * @param DetilRencanaKPI $detail
     * @param Attachment $attachment
     * @param KRAKPIDetail $penurunanKPI
     */
    public function __construct(
        HeaderRencanaKPI $header,
        DetilRencanaKPI $detail,
        Attachment $attachment,
        KRAKPIDetail $penurunanKPI
    ) {
        $this->header = $header;
        $this->detail = $detail;
        $this->attachment = $attachment;
        $this->penurunanKPI = $penurunanKPI;
    }

    /**
     * @param array $filter
     * @return mixed
     */
    private function datatableRencanaBuilder(array $filter = [])
    {
        $query = DB::table('Tr_KPIRencanaHeader')->select([
            'Tr_KPIRencanaHeader.ID',
            'Tr_KPIRencanaHeader.NPK',
            'Tr_KPIRencanaHeader.Tahun',
            'Tr_KPIRencanaHeader.CreatedBy',
            'Tr_KPIRencanaHeader.CreatedOn',
            'Tr_KPIRencanaHeader.Grade',
            'Tr_KPIRencanaHeader.IDStatusDokumen',
            'VL_StatusDokumen.StatusDokumen',
            'Ms_Karyawan.NamaKaryawan',
            'Ms_MasterPosition.PositionTitle',
            'Ms_UnitKerja.Deskripsi'
        ])
            ->leftJoin('Ms_Karyawan', 'Tr_KPIRencanaHeader.NPK', '=', 'Ms_Karyawan.NPK')
            ->leftJoin('VL_StatusDokumen', 'Tr_KPIRencanaHeader.IDStatusDokumen', '=', 'VL_StatusDokumen.ID')
            ->leftJoin('View_OrganizationalAssignment', 'Ms_Karyawan.NPK', '=', 'View_OrganizationalAssignment.NPK')
            ->leftJoin('Ms_MasterPosition', 'Ms_MasterPosition.PositionID', '=', 'View_OrganizationalAssignment.PositionID')
            ->leftJoin('Ms_UnitKerja', 'Ms_MasterPosition.KodeUnitKerja', '=', 'Ms_UnitKerja.CostCenter')
            ->when(array_key_exists('tahun', $filter), function ($query) use ($filter) {
                $query->where('Tr_KPIRencanaHeader.Tahun', $filter['tahun']);
            });

        return $query;
    }

    /**
     * @param $user
     * @param array $filter
     * @return mixed
     */
    public function datatable($user, array $filter = [])
    {
        $query = $this->datatableRencanaBuilder($filter)
                ->when($user->UserRole->Role != 'Administrator', function($query) use ($user) {
                    $query->where('Tr_KPIRencanaHeader.CreatedBy', $user->NPK);
                })
                ->when($user->UserRole->Role == 'Administrator', function($query) {
                    $query->whereNotIn('Tr_KPIRencanaHeader.IDStatusDokumen', [5]);
                });

        return $query;
    }

    /**
     * @param $user
     * @param array $filters
     * @return mixed
     */
    public function datatableBawahanLangsung($user, array $filters = [])
    {
       return $this->datatableRencanaBuilder($filters)->where('NPKAtasanLangsung', $user->NPK)
                ->whereIn('IDStatusDokumen', [1, 2, 3, 4]);
    }

    /**
     * @param $user
     * @param array $filters
     * @return mixed
     */
    public function datatableBawahanTakLangsung($user, array $filters = [])
    {
        return $this->datatableRencanaBuilder($filters)->where('NPKAtasanBerikutnya', $user->NPK)
                ->whereIn('IDStatusDokumen', [1, 2, 3, 4]);
    }

    /**
     * @param $user
     * @param $status
     * @return int
     */
     public function countPendingDocument($user, $status)
     {
         $query = $this->header->select('ID');
         if ($status == 2) {
             $query->where('IDStatusDokumen', 2)->where('NPKAtasanLangsung', $user->NPK);
         } elseif ($status == 3) {
             $query->where('IDStatusDokumen', 3)->where('NPKAtasanBerikutnya', $user->NPK);
         }
         return $query->count();
     }

    /**
     * @param $status
     * @return int
     */
     public function countByStatusDocument($status, $year)
     {
         return $this->header->where('IDStatusDokumen', $status)->where('Tahun', $year)->count('ID');
     }

    /**
     * @param $status
     * @param $year
     * @param $npkAtasan
     * @return int
     */
     public function countByStatusDocumentBawahan($status, $year, $npkAtasan)
     {
         return $this->header->where(function ($query) use ($npkAtasan) {
             $query->where('NPKAtasanLangsung', $npkAtasan)->orWhere('NPKAtasanBerikutnya', $npkAtasan);
         })->where('IDStatusDokumen', $status)->where('Tahun', $year)->count('ID');
     }


    /**
     * [NEED QUERY OPTIMIZE!]
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->header->find($id);
    }

    /**
     * [NEED QUERY OPTIMIZE!]
     * @param $id
     * @return mixed
     */
    public function findKaryawanById($id)
    {
        return $this->header->with(['karyawan','karyawanatasanlangsung','karyawanatasanberikutnya'])->find($id);
    }

    /**
     * [NEED QUERY OPTIMIZE!]
     * @param $id
     * @return mixed
     */
    public function findByIdHeaderRealisasi($id)
    {
        return HeaderRealisasiKPI::where('IDKPIRencanaHeader', $id)->first();
    }
    /**
     * [NEED QUERY OPTIMIZE!]
     * @param $id
     * @return mixed
     */
    public function findDetailById($id)
    {
        return $this->detail->findOrFail($id);
    }
    /**
     * [NEED QUERY OPTIMIZE!]
     * @param $id
     * @return mixed
     */
    public function findHeaderByIdDetail($id)
    {    
        return $this->detail->findOrFail($id)->headerrencana()->first();
    }

    /**
     * [NEED QUERY OPTIMIZE!]
     * @param $id
     * @return mixed
     */
    public function findParentSplit($id)
    {    
        return $this->detail->where("IDSplitParent", $id)->first();
    }

    /**
     * [NEED QUERY OPTIMIZE!]
     * @param $id
     * @return mixed
     */
    public function findSumBobotSplit($id)
    {    
        return $this->detail->where("IDSplitParent", $id)->sum('Bobot');
    }

    public function findSumTargetSplit($id)
    {
        return $this->detail->where("IDSplitParent", $id)->sum('Target12');
    }

    /**
     * @param $attachmentId
     * @return mixed
     */
    public function findAttachmentById($attachmentId)
    {
        return $this->attachment->where('ID', $attachmentId)->first();
    }

    /**
     * @param $npk
     * @return mixed
     */
    public function findApprovedRencanaByNPK($npk)
    {
        return $this->header->approved()->where('NPK', $npk)->orderBy('Tahun', 'desc')->first();
    }

    /**
     * @param $npk
     * @param $periodeYear
     * @return mixed
     */
    public function findApprovedRencanaByNPKTahun($npk, $periodeYear)
    {
        return $this->header->approved()->where('NPK', $npk)->where('Tahun', $periodeYear)->orderBy('Tahun', 'desc')->first();
    }

    /**
     * @param $idHeader
     * @return mixed
     */
    public function getAllDetail($idHeader, $withTaskForce = true)
    {
        $builder = $this->header->select('ID')->where('ID', $idHeader)->first()
                ->detail()->with([
                    'aspekkpi' => function ($query) {
                        $query->select('ID', 'AspekKPI');
                    }, 'persentaserealisasi' => function ($query) {
                        $query->select('ID', 'PersentaseRealisasi');
                    }, 'satuan' => function ($query) {
                        $query->select('ID', 'Satuan');
                    }, 'jenisappraisal' => function ($query) {
                        $query->select('ID', 'JenisAppraisal');
                    }, 'penurunan', 'kpiatasan'
                ]);
        if (! $withTaskForce) {
            $builder->nonTaskForce();
        }
        return $builder->select('ID', 'DeskripsiKPI', 'DeskripsiKRA', 'Target1', 'Target2', 'Target3', 'Target4', 'Target5', 'Target6', 'Target7', 'Target8', 'Target9', 'Target10', 'Target11', 'Target12', 'Bobot', 'IDKodeAspekKPI', 'IDPersentaseRealisasi', 'IDSatuan', 'IDJenisAppraisal', 'IsKRABawahan', 'IDKRAKPIRencanaDetil', 'Keterangan', 'CreatedOn', 'IDSplitParent')
        // ->get()->sortBy('IDKodeAspekKPI')->groupBy('IDKodeAspekKPI')->flatten();
         ->orderBy("CreatedOn", "desc")->get()->groupBy('IDKodeAspekKPI')->flatten();
    }

    /**
     * @param $headerId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findItemIsKRA($headerId)
    {
        return $this->detail->where('IDKPIRencanaHeader', $headerId)->where('IsKRABawahan', 1)->get();
    }

    /**
     * @param $idHeaderRencana
     * @param int $isUnitKerja
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getForCreatingRealization($idHeaderRencana, $isUnitKerja = 0)
    {
        $builderDetail = $this->detail->where('IDKPIRencanaHeader', $idHeaderRencana);
        if ($isUnitKerja == 1) {
            $builderDetail->nonTaskForce();
        }
        return $builderDetail->get();
    }

    /**
     * @param $npk
     * @param string $typeBawahan
     * @return int
     */
    public function countWaitingDocumentByAtasan($npk, $typeBawahan = 'langsung')
    {
        $builder = $this->header->select('ID');
        if ($typeBawahan === 'langsung') {
            $builder->where('NPKAtasanLangsung', $npk)->where('IDStatusDokumen', 2);
        } else {
            $builder->where('NPKAtasanBerikutnya', $npk)->where('IDStatusDokumen', 3);
        }
        return $builder->count();
    }

    /**
     * @param $npk
     * @param $idStatusDokumen
     * @param null $tahun
     * @return int
     */
    public function countStatusUpdatedDocumentBy($npk, $idStatusDokumen, $tahun = null)
    {
        $builder = $this->header->select('ID');
        if ($tahun !=null) {
            $builder->where('Tahun', $tahun);
        }
        switch ($idStatusDokumen) {
            // Created by
            case 1:
                $builder->where('CreatedBy', $npk)->where('IDStatusDokumen', $idStatusDokumen);
                break;
            // Registered by
            case 2:
                $builder->where('RegisteredBy', $npk)->where('IDStatusDokumen', $idStatusDokumen);
                break;
            // Confirmed by
            case 3:
                $builder->where('ConfirmedBy', $npk)->where('IDStatusDokumen', $idStatusDokumen);
                break;
            // Approved by
            case 4:
                $builder->where('ApprovedBy', $npk)->where('IDStatusDokumen', $idStatusDokumen);
                break;
        }
        return $builder->count();
    }

    /**
     * @param $headerId
     * @return mixed
     */
    public function countBobotItem($headerId, $isTaskForce = false)
    {
        $builder = $this->detail->where('IDKPIRencanaHeader', $headerId);
        if ($isTaskForce) {
            $builder->where('IDKodeAspekKPI', 4);
        } else {
            $builder->whereNotIn('IDKodeAspekKPI', [4]);
        }
        return $builder->sum('Bobot');
    }

    /**
     * @param $npk
     * @return mixed
     */
    public function countBobotCascadeItem($npk)
    {
        return $this->detail->whereHas('penurunan', function ($query) use ($npk) {
            $query->where('NPKBawahan', $npk);
        })->sum('Bobot');
    }

    /**
     * @param $headerId
     * @param null $aspekID
     * @return int
     */
    public function countItemKPI($headerId, $aspekID = null)
    {
        $builder = $this->detail->where('IDKPIRencanaHeader', $headerId);
        if (isset($aspekID)) {
            $builder->where('IDKodeAspekKPI', $aspekID);
        }
        return $builder->select('ID')->count();
    }

    /**
     * @param $headerId
     * @return int
     */
    public function countPenurunan($headerId)
    {
        $builder = $this->detail->where('IDKPIRencanaHeader', $headerId)->where('IsKRABawahan', 1);
        return $builder->select('ID')->count();
    }

    /**
     * @param $headerId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllPenurunanItem($headerId)
    { 
        $selectedCascade = [
            'ID', 'IDKPIAtasan', 'NPKBawahan', 'Ms_Karyawan.NamaKaryawan', 'PersentaseKRA', 'Keterangan', 'CreatedBy', 'CreatedOn', 'IsApproved',
            'Target1', 'Target2', 'Target3', 'Target4', 'Target5', 'Target6',
            'Target7', 'Target8', 'Target9', 'Target10', 'Target11', 'Target12'
        ];
        $selectedDetail = [
            'DeskripsiKPI', 'ID', 'Bobot', 'IDSatuan'
        ];
        $selectedSatuan = ['ID', 'Satuan'];

        $collection = $this->penurunanKPI->leftJoin('Ms_Karyawan', 'Tr_KRAKPIRencanaDetil.NPKBawahan', '=', 'Ms_Karyawan.NPK')->whereHas('detailkpi', function ($query) use ($headerId, $selectedDetail) {
            $query->where('IDKPIRencanaHeader', $headerId)->where('IsKRABawahan', 1)->select($selectedDetail);
        })->with(['detailkpi.satuan' => function ($query) use ($selectedSatuan) {
            $query->select($selectedSatuan);
        }, 'detailkpi.jenisappraisal'])->select($selectedCascade)->orderBy("NPKBawahan", "desc")->get();
        
        return $collection;

    }

    /**
     * @param $npkBawahan
     * @param bool $isApproved
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllPenurunanItemTo($npkBawahan, $isApproved = false)
    {
        $selectedCascade = [
            'ID', 'IDKPIAtasan', 'NPKBawahan', 'PersentaseKRA', 'Keterangan', 'CreatedBy', 'CreatedOn', 'IsApproved'
        ];
        $selectedDetail = [
            'DeskripsiKPI', 'ID', 'Bobot', 'IDSatuan', 'Target1', 'Target2', 'Target3', 'Target4', 'Target5', 'Target6',
            'Target7', 'Target8', 'Target9', 'Target10', 'Target11', 'Target12', 'IDKPIRencanaHeader'
        ];
        $selectedSatuan = ['ID', 'Satuan'];
        $collection = $this->penurunanKPI->with(['detailkpi.satuan' => function ($query) use ($selectedSatuan) {
            $query->select($selectedSatuan);
        }, 'detailkpi' => function ($query) use ($selectedDetail) {
            $query->select($selectedDetail);
        }, 'detailkpi.headerrencana' => function ($query) {
            $query->select('IDStatusDokumen', 'ID');
        }])->where('NPKBawahan', $npkBawahan)->select($selectedCascade);

        if ($isApproved) {
            $collection->where('isApproved', true);
        }

        return $collection->get();
    }

    /**
     * @param $npkAtasanLangsung
     * @param bool $isApproved
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllPenurunanItemFrom($id, $isApproved = false)
    {
        // $selectedCascade = [
        //     'ID', 'IDKPIAtasan', 'NPKBawahan', 'PersentaseKRA', 'Keterangan', 'CreatedBy', 'CreatedOn', 'IsApproved'
        // ];
        // $selectedDetail = [
        //     'DeskripsiKPI', 'ID', 'Bobot', 'IDSatuan', 'Target1', 'Target2', 'Target3', 'Target4', 'Target5', 'Target6',
        //     'Target7', 'Target8', 'Target9', 'Target10', 'Target11', 'Target12', 'IDKPIRencanaHeader'
        // ];
        // $selectedSatuan = ['ID', 'Satuan'];
        // $collection = $this->penurunanKPI->with(['detailkpi.satuan' => function ($query) use ($selectedSatuan) {
        //     $query->select($selectedSatuan);
        // }, 'detailkpi' => function ($query) use ($selectedDetail) {
        //     $query->select($selectedDetail);
        // }, 'detailkpi.headerrencana' => function ($query) {
        //     $query->select('IDStatusDokumen', 'ID');
        // }])->where('NPKAtasanLangsung', $npkAtasanLangsung)->select($selectedCascade);

        // if ($isApproved) {
        //     $collection->where('isApproved', true);
        // }
        // return $collection->get();

        $data = \DB::table("Tr_KRAKPIRencanaDetil")->where("IDKPIAtasan", $id)->get(); 

        return $data;
    }

    // public getAllPenurunanItemAtasan($id){
    //     $data = \DB::table("Tr_KRAKPIRencanaDetil")->where("IDKPIAtasan", $id)->get();
    //     return $data;
    // }

    /**
     * @param $idCascade
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function findPenurunanById($idCascade)
    {
        return $this->penurunanKPI->find($idCascade);
    }

     /**
     * @param $idCascade
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function getPenurunanById($idCascade)
    {
        return $this->penurunanKPI->where("IDKPIAtasan", $idCascade)->orderBy("PersentaseKRA", "DESC")->get();
    }


    /**
     * @param $npk
     * @param $tahun
     * @return int
     */
    public function checkRencanaAlreadyExist($npk, $tahun)
    {
        return $this->header->where('NPK', $npk)->where('Tahun', $tahun)->whereNotIn('IDStatusDokumen', [5])->exists();
    }

    /**
     * @param $headerRencana
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getApprovedCascadeItem($headerRencana)
    {
        return $this->penurunanKPI->whereHas('detailkpi.headerrencana', function ($query) use ($headerRencana) {
            $query->where('Tahun', $headerRencana->Tahun)->where('IDStatusDokumen', 4);
        })->with(['detailkpi'])->where('IsApproved', true)->where('NPKBawahan', $headerRencana->NPK)->get();
    }

    /**
     * @param $npkBawahan
     * @param $idKPIAtasan
     * @return bool
     */
    public function isCascadeItemAlreadyAssigned($npkBawahan, $idKPIAtasan)
    {
        return $this->penurunanKPI->where('NPKBawahan', $npkBawahan)->where('IDKPIAtasan', $idKPIAtasan)->exists();
    }

    /**
     * @param $idKPIAtasan
     * @return mixed
     */
    public function getPercentageCascadedItems($idKPIAtasan)
    {
        return $this->penurunanKPI->where('IDKPIAtasan', $idKPIAtasan)->where('IsApproved', 0)->sum('PersentaseKRA');
    }

    /**
     * @param $idKPIAtasan
     * @param $idCascaded
     * @return mixed
     */
    public function getPercentageCascadedItemExcept($idKPIAtasan, $idCascaded)
    {
        return $this->penurunanKPI->where('IDKPIAtasan', $idKPIAtasan)
                    ->where('IsApproved', 0)
                    ->whereNotIn('ID', [$idCascaded])->sum('PersentaseKRA');
    }

    /**
     * @param $periodeTahun
     * @return int
     */
    public function countProgressKPI($periodeTahun, $status = 'inprogress')
    {
        $builder = $this->header->where('Tahun', $periodeTahun);
        if ($status === 'inprogress') {
            $builder->whereNotIn('IDStatusDokumen', [4, 5]);
        } elseif ($status === 'approved') {
            $builder->where('IDStatusDokumen', 4);
        }
        return $builder->count();
    }

    //======= tambah baru ===============
    public function isAvailablePenurunan($detil)
    { 
        $penurunan = $this->getAllPenurunanItemFrom($detil->ID);
        if (count($penurunan) > 0) {
            throw new DomainException('Masih ada data item penurunan yang belum Anda hapus pada item KPI ini, silakan hapus terlebih dahulu.');
        }
        return true;
    }

    public function updateBawahan($header){  

        $position = $this->getPosition($header->NPK);
        $shift = $position->Shift;
        $substr = substr($position->PositionAbbreviation, 0, 8);
        $PositionAbbreviation = rtrim($substr, 0); 
        
        if($position->PositionAbbreviation == '13240000ECCDSN') // spesial pak muslih
        {
             $employees = \DB::table('View_karyawan')
                            ->where("KodeUnitKerja", "D003100000")
                            ->where("PositionAbbreviation", 'LIKE', '132%FN%')
                            ->get();
        }else{
            $employees = \DB::table('View_karyawan')
                            ->where('PositionAbbreviation', 'LIKE', $PositionAbbreviation.'%');

                            if(\Auth::user()->IDRole == 8){
                                $employees = $employees->get();
                            }else{
                                $employees = $employees->where("Shift", $shift)->get();
                            }
        }         

        if(count($employees) > 0){
            foreach ($employees as $employee) {
                try{
                    \DB::table('Tr_KPIRencanaHeader')->where('NPK', $employee->NPK)
                            ->where('IDStatusDokumen', '!=', 1)
                            ->where('Tahun', $header->Tahun)
                            ->update(['IDStatusDokumen' => 1]); 
                }catch(\Exceptions $e){
                    throw new DomainException('Update KPI bawahan tidak berhasil.');
                } 
            }  

            return true;

        }else{
            return true;
        }
    }

    public function getPosition($npk){  

        $data = \DB::table('View_karyawan')->where('NPK', $npk)->first();
        if($data){
            return $data;
        }else{

            $direksi = \DB::table('Ms_Direksi')->where('Npk', $npk)->first(); 
            if($direksi){
                return $direksi;
            } else {
                throw new DomainException('Anda tidak ditemukan sebagai karyawan'); 
            } 
        }
    }

    public function findSplitGroup($id)
    { 
        $data = DetilRencanaKPI::findOrFail($id);

        if($data->is_split == 1) // parent split
        {
            return \DB::table("Tr_KPIRencanaDetil") 
                ->where("IDSplitParent", $id)
                ->orWhere("ID",  $id)
                ->orderBy("is_split", "desc")
                ->get();
        }
        else if($data->IDSplitParent != null)
        {
            return \DB::table("Tr_KPIRencanaDetil") 
                ->where("IDSplitParent", $data->IDSplitParent)
                ->orWhere("ID", $data->IDSplitParent)
                ->orderBy("is_split", "desc")
                ->get(); 
        }else{
            return false;
        }
    }
}
