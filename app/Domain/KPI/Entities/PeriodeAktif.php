<?php

namespace App\Domain\KPI\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domain\User\Entities\User;

class PeriodeAktif extends Model
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
    protected $table = 'Ms_PeriodeAktif';

    /**
     * @var array
     */
    protected $fillable = [
        'ID', 'NamaPeriode', 'Tahun', 'StartDate', 'EndDate', 'IDJenisPeriode','StatusPeriode', 'Aktif',
        'Keterangan', 'CreatedBy', 'CreatedOn', 'UpdatedBy', 'UpdatedOn'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    public $casts = [
        'boolean' => 'Aktif'
    ];

    /**
     *
     */
    public function jenisperiode()
    {
        return $this->belongsTo(JenisPeriode::class, 'IDJenisPeriode');
    }
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
}
