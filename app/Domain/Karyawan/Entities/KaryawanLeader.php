<?php

namespace App\Domain\Karyawan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Domain\KPI\Entities\UnitKerja;

class KaryawanLeader extends Model
{  
    /**
     * @var string
     */
    protected $connection = 'leader';

    /**
     * @var string
     */
    public $primaryKey = 'PERSONNEL_NUMBER';

    /**
     * @var string
     */
    protected $table = 'view_extended_ekpi';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var bool
     */
    public $incrementing = false; 
}
