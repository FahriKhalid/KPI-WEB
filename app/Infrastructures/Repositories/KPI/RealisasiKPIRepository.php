<?php

namespace App\Infrastructures\Repositories\KPI;

use App\Domain\Realisasi\Entities\Attachment;
use App\Domain\Realisasi\Entities\DetilRealisasiKPI;
use App\Domain\Realisasi\Entities\HeaderRealisasiKPI;
use App\Domain\Realisasi\Entities\ValidasiUnitKerja;
use App\Domain\Rencana\Entities\HeaderRencanaKPI;
use Illuminate\Support\Facades\DB;

class RealisasiKPIRepository
{
    /**
     * @var HeaderRealisasiKPI
     */
    protected $header;

    /**
     * @var HeaderRencanaKPI
     */
    protected $headerRencana;

    /**
     * @var DetilRealisasiKPI
     */
    protected $detail;

    /**
     * @var Attachment
     */
    protected $attachment;

    /**
     * @var ValidasiUnitKerja
     */
    protected $validasiUnitKerja;

    /**
     * RealisasiKPIRepository constructor.
     *
     * @param HeaderRealisasiKPI $header
     * @param DetilRealisasiKPI $detail
     * @param Attachment $attachment
     * @param ValidasiUnitKerja $validasiUnitKerja
     * @param HeaderRencanaKPI $headerRencana
     */
    public function __construct(
        HeaderRealisasiKPI $header,
        DetilRealisasiKPI $detail,
        Attachment $attachment,
        ValidasiUnitKerja $validasiUnitKerja,
        HeaderRencanaKPI $headerRencana
    ) {
        $this->header = $header;
        $this->headerRencana = $headerRencana;
        $this->detail = $detail;
        $this->attachment = $attachment;
        $this->validasiUnitKerja = $validasiUnitKerja;
    }

    /**
     * [NEED QUERY OPTIMIZE!]
     * @param $id
     * @return HeaderRealisasiKPI
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
        return $this->header->with(['karyawan', 'karyawanatasanlangsung', 'karyawanatasanberikutnya'])->find($id);
    }

    /**
     * [NEED QUERY OPTIMIZE!]
     * @param $id
     * @return mixed
     */
    public function findDetailById($id)
    {
        return $this->detail->where('ID', $id)->first();
    }

    /**
     * @param $user
     * @param array $filters
     * @param int $isUnitKerja
     * @return mixed
     */
    public function datatable($user, array $filters = [], $isUnitKerja = 0)
    {
        return DB::table('Tr_KPIRealisasiHeader')->select([
                    'Tr_KPIRealisasiHeader.ID', 'Tr_KPIRealisasiHeader.IDStatusDokumen', 'Tr_KPIRealisasiHeader.IDMasterPosition',
                    'Tr_KPIRealisasiHeader.KodePeriode', 'Tr_KPIRealisasiHeader.Grade', 'Tr_KPIRealisasiHeader.CreatedBy',
                    'Tr_KPIRealisasiHeader.CreatedOn', 'Tr_KPIRealisasiHeader.NPK', 'Tr_KPIRealisasiHeader.Tahun',
                    'Tr_KPIRealisasiHeader.NilaiAkhir', 'Tr_KPIRealisasiHeader.IDJenisPeriode',
                    'Tr_KPIRealisasiHeader.NilaiAkhirNonTaskForce', 'Tr_KPIRealisasiHeader.NilaiValidasiNonTaskForce',
                    'VL_StatusDokumen.StatusDokumen', 'Ms_Karyawan.NamaKaryawan',
                    'Ms_MasterPosition.PositionTitle', 'Ms_UnitKerja.Deskripsi'
                ])
                ->leftJoin('Ms_Karyawan', 'Tr_KPIRealisasiHeader.NPK', '=', 'Ms_Karyawan.NPK')
                ->leftJoin('VL_StatusDokumen', 'Tr_KPIRealisasiHeader.IDStatusDokumen', '=', 'VL_StatusDokumen.ID')
                ->leftJoin('View_OrganizationalAssignment', 'Ms_Karyawan.NPK', '=', 'View_OrganizationalAssignment.NPK')
                ->leftJoin('Ms_MasterPosition', 'Ms_MasterPosition.PositionID', '=', 'View_OrganizationalAssignment.PositionID')
                ->leftJoin('Ms_UnitKerja', 'Ms_MasterPosition.KodeUnitKerja', '=', 'Ms_UnitKerja.CostCenter')
                ->leftJoin('Ms_PeriodeAktif', 'Tr_KPIRealisasiHeader.KodePeriode', '=', 'Ms_PeriodeAktif.ID')
                ->leftJoin('VL_JenisPeriode', 'Ms_PeriodeAktif.IDJenisPeriode', '=', 'VL_JenisPeriode.ID')
                ->when(($isUnitKerja == 1), function($query) {
                    $query->whereIn('Target', [3, 6, 9, 12]);
                }, function($query) {
                    $query->where('IsUnitKerja', 0);
                })
                ->when($user->UserRole->Role != 'Administrator', function($query) use ($user) {
                    $query->where('Tr_KPIRealisasiHeader.CreatedBy', $user->NPK);
                })
                ->when($user->UserRole->Role == 'Administrator', function($query) use ($user) {
                    $query->whereNotIn('IDStatusDokumen', [5]);
                })
                ->when(array_key_exists('tahun', $filters), function($query) use ($filters) {
                    $query->where('Tr_KPIRealisasiHeader.Tahun', $filters['tahun']);
                })
                ->when(array_key_exists('kodeperiode', $filters), function($query) use ($filters) {
                    $query->where('VL_JenisPeriode.ID', $filters['kodeperiode']);
                });
    }

    /**
     * @param $user
     * @return $this
     */
    public function datatableUnitKerja($user)
    {
        return $this->header->with([
            'statusdokumen' => function($query) {
                $query->select('ID', 'StatusDokumen');
            },
            'masterposition' => function($query) {
                $query->select('PositionID', 'PositionTitle','KodeUnitKerja');
            },
            'masterposition.unitkerja' => function($query) {
                $query->select('CostCenter', 'Deskripsi');
            },
            'periodeaktif.jenisperiode' => function($query) {
                $query->select('ID', 'KodePeriode');
            },
            'karyawan' => function($query) {
                $query->select('NPK', 'NamaKaryawan');
            },
            'detail' => function ($query) {
                $query->select('ID', 'IDKPIRealisasiHeader', 'PersentaseRealisasi');
            }])
            ->select('Tr_KPIRealisasiHeader.ID', 'IDStatusDokumen', 'IDMasterPosition', 'KodePeriode', 'NPK',
                'Grade', 'CreatedBy', 'CreatedOn', 'Tr_KPIRealisasiHeader.NPK', 'Tr_KPIRealisasiHeader.Tahun',
                'Tr_KPIRealisasiHeader.NilaiAkhir', 'IDJenisPeriode',
                'Tr_KPIRealisasiHeader.NilaiAkhirNonTaskForce', 'Tr_KPIRealisasiHeader.NilaiValidasiNonTaskForce')
            ->whereIn('Target', [3, 6, 9, 12])
            ->when($user->UserRole->Role != 'Administrator', function($query) use ($user) {
                $query->where('CreatedBy', $user->NPK);
            }, function($query) {
                $query->whereNotIn('IDStatusDokumen', [5]);
            });
    }

    /**
     * @param $user
     * @param array $filters
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function datatableBawahanLangsung($user, $filters = [])
    {
        $query = $this->header->with([
            'statusdokumen',
            'masterposition',
            'masterposition.unitkerja',
            'periodeaktif',
            'karyawan',
            'jenisperiode',
            'periodeaktif.jenisperiode',
            'detail'
        ])
        ->select('Tr_KPIRealisasiHeader.*')
        ->where('NPKAtasanLangsung', $user->NPK)
        ->whereIn('IDStatusDokumen', [1, 2, 3, 4]);

        // build filters
        if (count($filters) > 0) {
            if (array_key_exists('jeniskpi', $filters)) {
                if ($filters['jeniskpi'] == 'individu') {
                    $query->individu();
                } elseif ($filters['jeniskpi'] == 'unitkerja') {
                    $query->unitKerja();
                } else {
                    $query->individu();
                }
            }

            if (array_key_exists('tahunperiode', $filters) && $filters['tahunperiode'] != '') {
                $query->where('Tahun', $filters['tahunperiode']);
            }

            if (array_key_exists('jenisperiode', $filters) && $filters['jenisperiode'] != '') {
                if ($filters['jenisperiode'] != 'all') {
                    $query->where('IDJenisPeriode', $filters['jenisperiode']);
                }
            }
        }
        return $query;
    }

    /**
     * @param $user
     * @return \Illuminate\Database\Query\Builder
     */
    public function datatableBawahanTakLangsung($user)
    {
        $query =  $this->header->with(['statusdokumen', 'masterposition', 'masterposition.unitkerja', 'periodeaktif', 'karyawan', 'periodeaktif.jenisperiode', 'jenisperiode'])
                                ->select('Tr_KPIRealisasiHeader.*')
                                ->where('NPKAtasanBerikutnya', $user->NPK)
                                ->whereIn('IDStatusDokumen', [1, 2, 3, 4]);
        return $query;
    }

    /**
     * @param $kodeunitkerjapenilai
     * @param int $statusDokumenRealisasi
     * @return mixed
     */
    public function datatableValidasiUnitKerjaByUnitKerjaPenilai($kodeunitkerjapenilai, $statusDokumenRealisasi = 4)
    {
        $builder = $this->header
            ->join('VL_JenisPeriode', 'Tr_KPIRealisasiHeader.IDJenisPeriode', '=', 'VL_JenisPeriode.ID')
            ->leftJoin('Ms_Karyawan', 'Tr_KPIRealisasiHeader.NPK', '=', 'Ms_Karyawan.NPK')
            ->leftJoin('View_OrganizationalAssignment', 'View_OrganizationalAssignment.NPK', '=', 'Ms_Karyawan.NPK')
            ->rightJoin('Ms_MasterPosition', function ($query) {
                $query->on('View_OrganizationalAssignment.PositionID', '=', 'Ms_MasterPosition.PositionID')
                    ->where('Ms_MasterPosition.PositionAbbreviation', 'LIKE', '%0000000DS');
            })
            ->leftJoin('Ms_UnitKerja', function ($query) {
                $query->on('Ms_MasterPosition.KodeUnitKerja', '=', 'Ms_UnitKerja.CostCenter');
            })
            ->rightJoin('Ms_MatriksValidasi', function ($query) use ($kodeunitkerjapenilai) {
                $query->on('Ms_UnitKerja.CostCenter', '=', 'Ms_MatriksValidasi.KodeUnitKerja')
                    ->where('Ms_MatriksValidasi.KodeUnitKerjaPenilai', $kodeunitkerjapenilai);
            })
            ->leftJoin('Tr_ValidasiUnitKerja', function ($query) use ($kodeunitkerjapenilai) {
                $query->on('Tr_KPIRealisasiHeader.ID', '=', 'Tr_ValidasiUnitKerja.IDKPIRealisasiHeader')
                    ->where('Tr_ValidasiUnitKerja.KodeUnitKerjaPenilai', $kodeunitkerjapenilai);
            })
            ->leftJoin('VL_StatusDokumen', 'Tr_ValidasiUnitKerja.IDStatusDokumen', '=', 'VL_StatusDokumen.ID')
            ->where('Tr_KPIRealisasiHeader.IDStatusDokumen', $statusDokumenRealisasi);

        return $builder->select([
            'Tr_KPIRealisasiHeader.ID',
            'Ms_UnitKerja.CostCenter',
            'Ms_UnitKerja.Deskripsi',
            'Tr_KPIRealisasiHeader.Tahun',
            'Tr_KPIRealisasiHeader.IDJenisPeriode',
            'Tr_KPIRealisasiHeader.NilaiAkhir',
            'VL_StatusDokumen.StatusDokumen',
            'Tr_ValidasiUnitKerja.ID as IDValidasi',
            'Tr_ValidasiUnitKerja.ValidasiUnitKerja',
            'Tr_ValidasiUnitKerja.ValidasiAkhir',
            'Ms_MasterPosition.PositionAbbreviation',
            'Tr_KPIRealisasiHeader.NilaiAkhirNonTaskForce',
            'Tr_KPIRealisasiHeader.NilaiValidasiNonTaskForce',
            'Ms_MatriksValidasi.KodeUnitKerjaPenilai',
            'VL_JenisPeriode.KodePeriode'
        ]);
    }

    /**
     * @param $npkAtasanLangsung
     * @param int $statusDokumenRealisasi
     * @return mixed
     */
    public function datatableValidasiUnitKerjaByNPKAtasan($npkAtasanLangsung, $statusDokumenRealisasi = 4)
    {
        $builder = $this->validasiUnitKerja
                    ->leftJoin('Tr_KPIRealisasiHeader', function ($query) use ($statusDokumenRealisasi) {
                        $query->on('Tr_ValidasiUnitKerja.IDKPIRealisasiHeader', '=', 'Tr_KPIRealisasiHeader.ID')
                            ->where('Tr_KPIRealisasiHeader.IDStatusDokumen', $statusDokumenRealisasi);
                    })
                    ->join('VL_JenisPeriode', 'Tr_KPIRealisasiHeader.IDJenisPeriode', '=', 'VL_JenisPeriode.ID')
                    ->join('Ms_Karyawan', 'Tr_KPIRealisasiHeader.NPK', '=', 'Ms_Karyawan.NPK')
                    ->join('View_OrganizationalAssignment', 'Ms_Karyawan.NPK', '=', 'View_OrganizationalAssignment.NPK')
                    ->join('Ms_MasterPosition', 'View_OrganizationalAssignment.PositionID', '=', 'Ms_MasterPosition.PositionID')
                    ->join('Ms_UnitKerja', 'Ms_MasterPosition.KodeUnitKerja', '=', 'Ms_UnitKerja.CostCenter')
                    ->join('VL_StatusDokumen', 'Tr_ValidasiUnitKerja.IDStatusDokumen', '=', 'VL_StatusDokumen.ID')
                    ->where('Tr_ValidasiUnitKerja.NPKAtasanLangsung', $npkAtasanLangsung)
                    ->with('headerrealisasi.masterposition.unitkerja.matriksvalidasi')
                    ->groupBy([
                        'Ms_UnitKerja.CostCenter', 
                        'Ms_UnitKerja.Deskripsi',
                        'Tr_KPIRealisasiHeader.Tahun',
                        'Tr_KPIRealisasiHeader.IDJenisPeriode',
                        'VL_StatusDokumen.StatusDokumen',
                        'VL_JenisPeriode.KodePeriode',
                        'Tr_ValidasiUnitKerja.IDKPIRealisasiHeader',
                    ]);

        return $builder->select(
            'Ms_UnitKerja.CostCenter',
            'Ms_UnitKerja.Deskripsi',
            'Tr_KPIRealisasiHeader.Tahun',
            'Tr_KPIRealisasiHeader.IDJenisPeriode',
            DB::raw('MAX(Tr_KPIRealisasiHeader.NilaiAkhirNonTaskForce) AS NilaiAkhirNonTaskForce'),
            'VL_StatusDokumen.StatusDokumen',
            DB::raw('MAX(Tr_ValidasiUnitKerja.ID) AS IDValidasi'),
            'Tr_ValidasiUnitKerja.IDKPIRealisasiHeader',
            DB::raw('MAX(Tr_KPIRealisasiHeader.NilaiValidasiNonTaskForce) AS NilaiValidasiNonTaskForce'),
            DB::raw('MAX(Tr_ValidasiUnitKerja.KodeUnitKerjaPenilai) AS KodeUnitKerjaPenilai'),
            'VL_JenisPeriode.KodePeriode',
            DB::raw('ROUND(AVG(ValidasiUnitKerja), 2) AS AvgValidasi'),
            DB::raw('COUNT(Tr_KPIRealisasiHeader.ID) AS TotalCountValidasi')
        );
    }

    /**
     * @param $npk
     * @param $headerRealisasiID
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function findValidasiByCreatedAndHeaderRealisasi($npk, $headerRealisasiID)
    {
        return $this->validasiUnitKerja->with(['headerrealisasi'])->where('IDKPIRealisasiHeader', $headerRealisasiID)->where('CreatedBy', $npk)->first();
    }

    /**
     * @param $year
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function allItemRealizationGroupByYear($user, $year, $isUnitKerja = 0)
    {
        return $this->headerRencana->select('ID')->where('NPK', $user->NPK)->where('Tahun', $year)
            ->with(['detail.satuan', 'detail.realisasidetil.headerrealisasikpi' => function ($query) use ($isUnitKerja) {
                $query->select('ID', 'IDStatusDokumen', 'IsKPIUnitKerja')->where('IsKPIUnitKerja', $isUnitKerja);
            }])->get();
    }

    /**
     * @param $headerRealisasiID
     * @param bool $isUnitKerja
     * @return \Illuminate\Support\Collection
     */
    public function allItemRealization($headerRealisasiID, $isUnitKerja = false)
    {
        return $this->detail->with([
            'detilrencana',
            'detilrencana.satuan' => function ($query) {
                $query->select('ID', 'Satuan');
            },
            'detilrencana.aspekkpi' => function ($query) {
                $query->select('ID', 'AspekKPI');
            },
            'detilrencana.persentaserealisasi' => function ($query) {
                $query->select('ID', 'PersentaseRealisasi', 'KodePersentaseRealisasi');
            },
            'detilrencana.jenisappraisal' => function ($query) {
                $query->select('ID', 'JenisAppraisal');
            },
            'rencanapengembangan'
        ])
            ->where('IDKPIRealisasiHeader', $headerRealisasiID)
            ->when($isUnitKerja, function ($query) {
                $query->whereHas('detilrencana', function ($query) {
                    $query->whereNotIn('IDKodeAspekKPI', [4]);
                });
            })
            ->get()
            ->sortBy('detilrencana.IDKodeAspekKPI')
            ->groupBy('detilrencana.IDKodeAspekKPI')->flatten();
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
        if ($tahun != null) {
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
     * @param $idHeader
     * @return mixed
     */
    public function getAllDetail($idHeader)
    {
        return $this->header->select('ID')->where('ID', $idHeader)->first()
            ->detail()->with([
                'aspekkpi' => function ($query) {
                    $query->select('ID', 'AspekKPI');
                }, 'persentaserealisasi' => function ($query) {
                    $query->select('ID', 'PersentaseRealisasi');
                }, 'satuan' => function ($query) {
                    $query->select('ID', 'Satuan');
                }, 'jenisappraisal' => function ($query) {
                    $query->select('ID', 'JenisAppraisal');
                }
            ])
            ->select('ID', 'DeskripsiKPI', 'DeskripsiKRA', 'Target1', 'Target2', 'Target3', 'Target4', 'Target5', 'Target6', 'Target7', 'Target8', 'Target9', 'Target10', 'Target11', 'Target12', 'Bobot', 'IDKodeAspekKPI', 'IDPersentaseRealisasi', 'IDSatuan', 'IDJenisAppraisal', 'IsKRABawahan')
            ->orderBy('CreatedOn', 'asc')
            ->get();
    }

    /**
     * @param $attachmentId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function findAttachmentById($attachmentId)
    {
        return $this->attachment->find($attachmentId);
    }

    /**
     * @param $npk
     * @param array $data
     * @return bool
     */
    public function isRealizationExists($npk, array $data)
    {
        $query = $this->header->where('NPK', $npk)
            ->where('Tahun', $data['Tahun']);
        if (array_key_exists('KodePeriode', $data)) {
            $query->where('KodePeriode', $data['KodePeriode']);
        } else {
            $query->where('IDJenisPeriode', $data['IDJenisPeriode']);
        }
        return $query->whereNotIn('IDStatusDokumen', [5])->exists();
    }

    /**
     * @param $idStatusDokumen
     * @param $idjenisperiode
     * @param $year
     * @return int
     */
    public function countByStatusDocument($idStatusDokumen, $year, $idjenisperiode)
    {
        return $this->header->where('IDJenisPeriode', $idjenisperiode)->where('Tahun', $year)
                    ->where('IDStatusDokumen', $idStatusDokumen)->count();
    }

    /**
     * @param $idStatusDokumen
     * @param $year
     * @param $idjenisperiode
     * @param $npkAtasan
     * @return int
     */
    public function countByStatusDocumentBawahan($idStatusDokumen, $year, $idjenisperiode, $npkAtasan)
    {
        return $this->header->where('IDJenisPeriode', $idjenisperiode)
                            ->where('Tahun', $year)
                            ->where('IDStatusDokumen', $idStatusDokumen)
                            ->where(function ($query) use ($npkAtasan) {
                                $query->where('NPKAtasanLangsung', $npkAtasan)->orWhere('NPKAtasanBerikutnya', $npkAtasan);
                            })->count();
    }

    /**
     * @param $periodeTahun
     * @param $IDJenisPeriode
     * @param string $status
     * @return int
     */
    public function countProgressRealisasiKPI($periodeTahun, $IDJenisPeriode, $status = 'inprogress')
    {
        $builder = $this->header->where('Tahun', $periodeTahun)->where('IDJenisPeriode', $IDJenisPeriode);
        if ($status === 'inprogress') {
            $builder->whereNotIn('IDStatusDokumen', [4, 5]);
        } elseif ($status === 'approved') {
            $builder->where('IDStatusDokumen', 4);
        }
        return $builder->count();
    }

    /**
     * @param $periodeTahun
     * @param $idJenisRencanaPengembangan
     * @return int
     */
    public function countRencanaPengembangan($periodeTahun, $idJenisRencanaPengembangan)
    {
        return $this->detail->whereHas('headerrealisasikpi', function ($query) use ($periodeTahun) {
            $query->where('Tahun', $periodeTahun);
        })->where('IDRencanaPengembangan', $idJenisRencanaPengembangan)->count();
    }
}
