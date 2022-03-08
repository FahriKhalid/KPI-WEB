<?php

namespace App\Domain\Realisasi\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Domain\KPI\Entities\RencanaPengembangan;
use App\Domain\Rencana\Entities\DetilRencanaKPI;

class DetilRealisasiKPI extends Model
{
    /**
     * @var string
     */
    public $primaryKey = 'ID';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $table = 'Tr_KPIRealisasiDetil';

    /**
     * @var array
     */
    protected $fillable = [
        'ID','IDKPIRealisasiHeader','IDPeriodeKPI','Realisasi','KonversiNilai','Keterangan',
        'CreatedBy','CreatedOn','UpdatedBy','UpdatedOn','Field1','Field2','Field3', 'IDKPIRencanaDetil', 'NilaiAkhir'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @return mixed
     */
    public function headerrealisasikpi()
    {
        return $this->belongsTo(HeaderRealisasiKPI::class, 'IDKPIRealisasiHeader', 'ID')->withDefault();
    }

    /**
     * @return mixed
     */
    public function detilrencana()
    {
        return $this->belongsTo(DetilRencanaKPI::class, 'IDRencanaKPIDetil', 'ID')->withDefault();
    }

    /**
     * @return mixed
     */
    public function rencanapengembangan()
    {
        return $this->belongsTo(RencanaPengembangan::class, 'IDRencanaPengembangan', 'ID')->withDefault();
    }
}
