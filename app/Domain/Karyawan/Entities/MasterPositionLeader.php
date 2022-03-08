<?php

namespace App\Domain\Karyawan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Domain\KPI\Entities\UnitKerja;

class MasterPositionLeader extends Model
{  
    /**
     * @var string
     */
    protected $connection = 'leader';

    /**
     * @var string
     */
    public $primaryKey = 'PositionID';

    /**
     * @var string
     */
    protected $table = 'View_MasterPosition';

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
