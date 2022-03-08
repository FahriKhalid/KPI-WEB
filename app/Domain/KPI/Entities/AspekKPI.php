<?php

namespace App\Domain\KPI\Entities;

use Illuminate\Database\Eloquent\Model;

class AspekKPI extends Model
{
    const AKTIF = true;
    const NONAKTIF = false;

    /**
     * @var string
     */
    public $primaryKey = 'ID';

    /**
     * @var string
     */
    protected $table = 'VL_AspekKPI';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'KodeAspekKPI', 'AspekKPI', 'Keterangan', 'Aktif'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'Aktif' => 'boolean'
    ];
}
