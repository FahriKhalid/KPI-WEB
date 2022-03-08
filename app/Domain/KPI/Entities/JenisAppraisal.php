<?php

namespace App\Domain\KPI\Entities;

use Illuminate\Database\Eloquent\Model;

class JenisAppraisal extends Model
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
    protected $table = 'VL_JenisAppraisal';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'KodeJenisAppraisal', 'JenisAppraisal', 'Keterangan', 'Aktif'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'Aktif' => 'boolean'
    ];
}
