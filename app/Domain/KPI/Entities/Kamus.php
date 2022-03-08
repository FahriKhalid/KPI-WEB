<?php

namespace App\Domain\KPI\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domain\User\Entities\User;

class Kamus extends Model
{
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    
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
    protected $table = 'Ms_KamusKPI';

    /**
     * @var array
     */
    protected $fillable = [
        'ID', 'KodeRegistrasi', 'KodeUnitKerja', 'IDAspekKPI', 'IDJenisAppraisal', 'IDPersentaseRealisasi', 'KPI',
        'Deskripsi', 'IDSatuan', 'Rumus', 'PeriodeLaporan', 'Keterangan', 'Status', 'AlasanReject', 'Field1', 'Field2',
        'Field3', 'Field4', 'Field5', 'CreatedBy', 'CreatedOn', 'UpdatedBy', 'UpdatedOn', 'ApprovedBy', 'ApprovedOn',
        'IndikatorHasil','IndikatorKinerja','SumberData','Jenis'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function aspekkpi()
    {
        return $this->belongsTo(AspekKPI::class, 'IDAspekKPI', 'ID')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function jenisappraisal()
    {
        return $this->belongsTo(JenisAppraisal::class, 'IDJenisAppraisal', 'ID')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function persentaserealisasi()
    {
        return $this->belongsTo(PersentaseRealisasi::class, 'IDPersentaseRealisasi', 'ID')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function perioderealisasi()
    {
        return $this->belongsTo(PeriodeRealisasi::class, 'IDPeriodeRealisasi', 'ID')->withDefault();
    }
    /**
     * @return BelongsTo
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'IDSatuan', 'ID')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function createdby()
    {
        return $this->belongsTo(User::class, 'CreatedBy', 'ID')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function updatedby()
    {
        return $this->belongsTo(User::class, 'UpdatedBy', 'ID')->withDefault();
    }

    /**
     * @return array
     */
    public static function mandatoryKPIRegistrationCodes()
    {
        return ['TRY01290191', 'TRY01290192', 'TRY01290193'];
    }
}
