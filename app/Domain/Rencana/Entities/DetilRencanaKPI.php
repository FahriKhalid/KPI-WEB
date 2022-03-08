<?php

namespace App\Domain\Rencana\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Domain\KPI\Entities\AspekKPI;
use App\Domain\KPI\Entities\JenisAppraisal;
use App\Domain\KPI\Entities\JenisPeriode;
use App\Domain\KPI\Entities\Kamus;
use App\Domain\KPI\Entities\PersentaseRealisasi;
use App\Domain\KPI\Entities\RencanaPengembangan;
use App\Domain\KPI\Entities\Satuan;
use App\Domain\Realisasi\Entities\DetilRealisasiKPI;
use App\Exceptions\DomainException;

class DetilRencanaKPI extends Model
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
    protected $table = 'Tr_KPIRencanaDetil';

    /**
     * @var array
     */
    protected $casts = [
        'IsKRABawahan' => 'boolean'
    ];

    protected $fillable = [
        'ID', 'IDJenisPeriode', 'IDKodeAspekKPI', 'Bobot'
    ];
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return mixed
     */
    public function headerrencana()
    {
        return $this->belongsTo(HeaderRencanaKPI::class, 'IDKPIRencanaHeader', 'ID')->withDefault();
    }

    /**
     * @return mixed
     */
    public function aspekkpi()
    {
        return $this->belongsTo(AspekKPI::class, 'IDKodeAspekKPI', 'ID')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function realisasidetil()
    {
        return $this->hasMany(DetilRealisasiKPI::class, 'IDRencanaKPIDetil', 'ID');
    }
    /**
     * @return mixed
     */
    public function jenisperiode()
    {
        return $this->belongsTo(JenisPeriode::class, 'IDJenisPeriode', 'ID')->withDefault();
    }

    /**
     * @return mixed
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'IDSatuan', 'ID')->withDefault();
    }

    /**
     * @return mixed
     */
    public function persentaserealisasi()
    {
        return $this->belongsTo(PersentaseRealisasi::class, 'IDPersentaseRealisasi', 'ID')->withDefault();
    }

    /**
     * @return mixed
     */
    public function jenisappraisal()
    {
        return $this->belongsTo(JenisAppraisal::class, 'IDJenisAppraisal', 'ID')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function penurunan()
    {
        return $this->hasMany(KRAKPIDetail::class, 'IDKPIAtasan');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kpiatasan()
    {
        return $this->belongsTo(KRAKPIDetail::class, 'IDKRAKPIRencanaDetil', 'ID')->withDefault();
    }

    /**
     * @return mixed
     */
    public function pengembangan()
    {
        return $this->belongsTo(RencanaPengembangan::class, 'IDRencanaPengembangan', 'ID')->withDefault();
    }

    /**
     * @return mixed
     */
    public function krakpicascade()
    {
        return $this->hasMany(KRAKPIDetail::class, 'IDKPIAtasan', 'ID');
    }

    /**
     * @return mixed
     */
    public function cascadedkrakpi()
    {
        return $this->belongsTo(KRAKPIDetail::class, 'IDKRAKPIRencanaDetil', 'ID')->withDefault();
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeNonTaskForce($query)
    {
        return $query->whereNotIn('IDKodeAspekKPI', [4]);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeTaskForce($query)
    {
        return $query->whereIn('IDKodeAspekKPI', [4]);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeAsKRABawahan($query)
    {
        return $query->where('IsKRABawahan', 1);
    }

    /**
     * @return bool
     */
    public function isAsKRABawahan()
    {
        return $this->IsKRABawahan == 1;
    }

    /**
     * @return bool
     */
    public function isCascaded()
    {
        return $this->IDKRAKPIRencanaDetil !== null;
    }

    /**
     * @return bool
     */
    public function isMandatory()
    {
        return in_array($this->KodeRegistrasiKamus, Kamus::mandatoryKPIRegistrationCodes());
    }

    /**
     * @return bool
     */
    public function isTaskForce()
    {
        return $this->IDKodeAspekKPI == 4;
    }

    /**
     * @return bool
     * @throws DomainException
     */
    public function isAllowedToDelete()
    {
        if ($this->isCascaded() || $this->isMandatory()) {
            throw new DomainException('KPI wajib untuk grade 1A & 2A atau KPI yang diturunkan kepada Anda tidak bisa dihapus.');
        }
        return true;
    }
}
