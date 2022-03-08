<?php

namespace App\Domain\KPI\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domain\Karyawan\Entities\MasterPosition;
use App\Domain\User\Entities\User;

class UnitKerja extends Model
{
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    
    /**
     * @var string
     */
    public $primaryKey = 'CostCenter';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $table = 'Ms_UnitKerja';

    /**
     * @var array
     */
    protected $fillable = [
        'CostCenter', 'Deskripsi', 'Keterangan', 'Aktif', 'Field1', 'Field2', 'Field3',
        'Field4', 'Field5', 'Rumus', 'PeriodeLaporan', 'Keterangan', 'Status', 'AlasanReject', 'Field1', 'Field2',
        'Field3', 'Field4', 'Field5', 'CreatedBy', 'CreatedOn', 'UpdatedBy', 'UpdatedOn'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $casts = [
        'Aktif' => 'boolean'
    ];

    /**
     * @return BelongsTo
     */
    public function createdby()
    {
        return $this->belongsTo(User::class, 'CreatedBy', 'ID')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function updatedby()
    {
        return $this->belongsTo(User::class, 'UpdatedBy', 'ID')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matriksvalidasi()
    {
        return $this->hasMany(MatrixValidation::class, 'KodeUnitKerja', 'CostCenter');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matriksvalidasipenilai()
    {
        return $this->hasMany(MatrixValidation::class, 'KodeUnitKerjaPenilai', 'CostCenter');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function positions()
    {
        return $this->hasMany(MasterPosition::class, 'KodeUnitKerja', 'CostCenter');
    }
}
