<?php

namespace App\Domain\KPI\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Realisasi\Entities\DetilRealisasiKPI;
use App\Domain\Rencana\Entities\DetilRencanaKPI;

class RencanaPengembangan extends Model
{
    const TRAINING = 'Training';
    const NONTRAINING = 'Non-Training';

    /**
     * @var string
     */
    public $primaryKey = 'ID';

    /**
     * @var string
     */
    protected $table = 'VL_RencanaPengembangan';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'RencanaPengembangan', 'Keterangan'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detilrealisasi()
    {
        return $this->hasMany(DetilRealisasiKPI::class, 'ID', 'IDRencanaPengembangan');
    }
}
