<?php

namespace App\Domain\KPI\Entities;

use Illuminate\Database\Eloquent\Model;

class MatrixValidation extends Model
{
    /**
     * @var string
     */
    public $primaryKey = 'ID';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $table = 'Ms_MatriksValidasi';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return $this
     */
    public function unitkerja()
    {
        return $this->belongsTo(UnitKerja::class, 'KodeUnitKerja', 'CostCenter')->withDefault();
    }

    /**
     * @return $this
     */
    public function unitkerjapenilai()
    {
        return $this->belongsTo(UnitKerja::class, 'KodeUnitKerjaPenilai', 'CostCenter')->withDefault();
    }
}
