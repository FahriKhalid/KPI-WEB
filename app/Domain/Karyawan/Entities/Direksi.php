<?php

namespace App\Domain\Karyawan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Domain\Karyawan\Services\PositionAbbreviation;
use App\Domain\Realisasi\Entities\HeaderRealisasiKPI;
use App\Domain\Rencana\Entities\HeaderRencanaKPI;
use App\Domain\User\Entities\User;

class Direksi extends Model
{
    use Notifiable;
    /**
     * @var string
     */
    protected $table = 'Ms_Direksi';

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @var string
     */
    public $primaryKey = 'Npk';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var bool
     */
    public $incrementing = false;
 
 
}
