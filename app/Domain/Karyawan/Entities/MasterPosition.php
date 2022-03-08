<?php

namespace App\Domain\Karyawan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Domain\KPI\Entities\UnitKerja;

class MasterPosition extends Model
{
    /**
     * @var string
     */
    public $primaryKey = 'ID';

    /**
     * @var string
     */
    protected $table = 'Ms_MasterPosition';

    /**
     * @var array
     */
    protected $fillable = [
        'ID', 'PositionID', 'PositionTitle', 'PositionAbbreviation', 'KodeUnitKerja', 'StatusAktif', 'Keterangan',
        'CreatedBy', 'CreatedOn', 'UpdatedBy', 'UpdatedOn'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $casts = [
        'StatusAktif' => 'boolean'
    ];

    /**
     * @return mixed
     */
    public function unitkerja()
    {
        return $this->belongsTo(UnitKerja::class, 'KodeUnitKerja', 'CostCenter')->withDefault();
    }

    /**
     * @return HasOne
     */
    public function organization()
    {
        return $this->hasOne(OrganizationalAssignment::class, 'PositionID', 'PositionID')->withDefault();
    }
}
