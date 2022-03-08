<?php

namespace App\Domain\Realisasi\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Karyawan\Entities\Karyawan;
use App\Domain\KPI\Entities\StatusDokumen;
use App\Domain\KPI\Entities\UnitKerja;
use App\Exceptions\DomainException;

class ValidasiUnitKerja extends Model
{
    /**
     * @var string
     */
    public $primaryKey = 'ID';

    /**
     * @var string
     */
    protected $table = 'Tr_ValidasiUnitKerja';

    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @return bool
     */
    public function isSubmitted()
    {
        return $this->IDStatusDokumen == 6;
    }

    /**
     * @return bool
     */
    public function isValidated()
    {
        return $this->IDStatusDokumen == 7;
    }

    /**
     * @return mixed
     */
    public function statusdokumen()
    {
        return $this->belongsTo(StatusDokumen::class, 'IDStatusDokumen', 'ID')->withDefault();
    }

    /**
     * @return mixed
     */
    public function unitkerjapenilai()
    {
        return $this->belongsTo(UnitKerja::class, 'KodeUnitKerjaPenilai', 'CostCenter')->withDefault();
    }

    /**
     * @return mixed
     */
    public function headerrealisasi()
    {
        return $this->belongsTo(HeaderRealisasiKPI::class, 'IDKPIRealisasiHeader', 'ID')->withDefault();
    }

    /**
     * @return mixed
     */
    public function createdby()
    {
        return $this->belongsTo(Karyawan::class, 'CreatedBy', 'NPK')->withDefault();
    }

    /**
     * @return mixed
     */
    public function validatedby()
    {
        return $this->belongsTo(Karyawan::class, 'ValidatedBy', 'NPK')->withDefault();
    }

    /**
     * @return bool
     * @throws DomainException
     */
    public function isEditable()
    {
        if ($this->IDStatusDokumen == 6) {
            return true;
        }
        throw new DomainException('Dokumen yang akan di edit harus berstatus SUBMITTED');
    }
}
