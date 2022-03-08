<?php

namespace App\Domain\KPI\Entities;

use Illuminate\Database\Eloquent\Model;

class JenisPeriode extends Model
{
    /**
     * boolean
     */
    const AKTIF = true;

    /**
     * boolean
     */
    const NONAKTIF = false;

    /**
     * @var string
     */
    public $primaryKey = 'ID';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'VL_JenisPeriode';

    /**
     * @var array
     */
    protected $fillable = [
        'JenisPeriode', 'KodePeriode', 'NamaPeriodeKPI', 'Aktif', 'Keterangan'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'Aktif' => 'boolean'
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeTriwulan($query)
    {
        return $query->whereIn('ID', [4, 5, 6, 7]);
    }
}
