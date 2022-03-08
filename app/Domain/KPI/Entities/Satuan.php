<?php

namespace App\Domain\KPI\Entities;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
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
    protected $table = 'VL_Satuan';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'KodeSatuan', 'Satuan', 'Keterangan', 'Aktif'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'Aktif' => 'boolean'
    ];
}
