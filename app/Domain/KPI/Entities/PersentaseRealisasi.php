<?php

namespace App\Domain\KPI\Entities;

use Illuminate\Database\Eloquent\Model;

class PersentaseRealisasi extends Model
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
    protected $table = 'VL_PersentaseRealisasi';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'KodePersentaseRealisasi', 'PersentaseRealisasi', 'Keterangan', 'Aktif'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'Aktif' => 'boolean'
    ];
}
