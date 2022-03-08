<?php

namespace App\Domain\KPI\Entities;

use Illuminate\Database\Eloquent\Model;

class PeriodeRealisasi extends Model
{
    /**
     * @var string
     */
    public $primaryKey = 'ID';

    /**
     * @var string
     */
    protected $table = 'VL_PeriodeRealisasi';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'ID', 'Tahun', 'IDJenisPeriode'
    ];

    /**
     * @return mixed
     */
    public function jenisPeriode()
    {
        return $this->belongsTo(JenisPeriode::class, 'IDJenisPeriode', 'ID')->withDefault();
    }
}
