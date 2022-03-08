<?php

namespace App\Domain\Rencana\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Karyawan\Entities\Karyawan;
use App\Domain\Karyawan\Entities\MasterPosition;
use App\Domain\KPI\Entities\StatusDokumen;
use App\Domain\Realisasi\Entities\HeaderRealisasiKPI;
use App\Exceptions\DomainException;

class HeaderRencanaKPI extends Model
{
    const REGISTERED = 'registered';
    const CONFIRMED = 'confirmed';
    const APPROVED = 'approved';
    const IDSTATUS_ALLOW_REGISTER = [1];
    const IDSTATUS_ALLOW_UNREGISTER = [2];
    const IDSTATUS_ALLOW_CONFIRM = [2];
    const IDSTATUS_ALLOW_UNCONFIRM = [2, 3];
    const IDSTATUS_ALLOW_APPROVE = [3];
    const IDSTATUS_ALLOW_UNAPPROVE = [3, 4];
    const IDSTATUS_ALLOW_CANCEL = [1, 2];

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
    protected $table = 'Tr_KPIRencanaHeader';

    /**
     * @var array
     */
    protected $fillable = [
        'ID', 'Tahun','NPK', 'IDMasterPosition', 'Grade', 'NPKAtasanLangsung', 'JabatanAtasanLangsung',
        'NPKAtasanBerikutnya', 'JabatanAtasanBerikutnya', 'IDStatusDokumen', 'Keterangan', 'CreatedBy', 'CreatedOn',
        'ApprovedBy', 'CreatedOn', 'CatatanUnapprove', 'ConfirmedBy', 'ConfirmedOn', 'RegisteredBy', 'RegisteredOn',
        'CanceledBy', 'CanceledOn', 'AlasanCancel', 'Field1', 'Field2', 'Field3', 'Field4', 'Field5'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return mixed
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'NPK', 'NPK')->withDefault();
    }

    /**
     * @return mixed
     */
    public function detail()
    {
        return $this->hasMany(DetilRencanaKPI::class, 'IDKPIRencanaHeader');
    }

    /**
     * @return mixed
     */
    public function detailGrouped()
    {
        return $this->hasMany(DetilRencanaKPI::class, 'IDKPIRencanaHeader');
    }

    /**
     * @return mixed
     */
    public function statusdokumen()
    {
        return $this->belongsTo(StatusDokumen::class, 'IDStatusDokumen', 'ID')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function masterposition()
    {
        return $this->belongsTo(MasterPosition::class, 'IDMasterPosition', 'PositionID')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'IDKPIRencanaHeader');
    }

    /**
     * @return mixed
     */
    public function realisasi()
    {
        return $this->hasMany(HeaderRealisasiKPI::class, 'IDKPIRencanaHeader');
    }

    /**
     *
     */
    public function karyawanatasanlangsung()
    {
        return $this->hasOne(Karyawan::class, 'NPK', 'NPKAtasanLangsung')->withDefault();
    }

    /**
     *
     */
    public function karyawanatasanberikutnya()
    {
        return $this->hasOne(Karyawan::class, 'NPK', 'NPKAtasanBerikutnya')->withDefault();
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeApproved($query)
    {
        return $query->where('IDStatusDokumen', 4);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeInProgress($query)
    {
        return $query->whereIn('IDStatusDokumen', [1, 2, 3]);
    }

    /**
     * @return bool
     * @throws DomainException
     */
    public function isAllowedToRegister()
    {
        if (in_array($this->IDStatusDokumen, self::IDSTATUS_ALLOW_REGISTER)) {
            return true;
        }
        throw new DomainException('Dokumen yang di-register harus berstatus DRAFT');
    }

    /**
     * @return bool
     */
    public function isDraft()
    {
        return ($this->IDStatusDokumen == 1);
    }

    /**
     * @return bool
     */
    public function isRegistered()
    {
        return ($this->IDStatusDokumen == 2);
    }

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        return ($this->IDStatusDokumen == 3);
    }

    /**
     * @return bool
     */
    public function isApproved()
    {
        return ($this->IDStatusDokumen == 4);
    }

    /**
     * @return bool
     * @throws DomainException
     */
    public function isAllowedToUnregister()
    {
        if (in_array($this->IDStatusDokumen, self::IDSTATUS_ALLOW_UNREGISTER)) {
            return true;
        }
        throw new DomainException('Dokumen yang di-unregister harus berstatus REGISTERED.');
    }

    /**
     * @return bool
     * @throws DomainException
     */
    public function isAllowedToCancel()
    {
        if (in_array($this->IDStatusDokumen, self::IDSTATUS_ALLOW_CANCEL)) {
            return true;
        }
        throw new DomainException('Dokumen yang di-cancel harus berstatus DRAFT atau REGISTERED.');
    }

    /**
     * @return bool
     * @throws DomainException
     */
    public function isAllowedToConfirm()
    {
        if (in_array($this->IDStatusDokumen, self::IDSTATUS_ALLOW_CONFIRM)) {
            return true;
        }
        throw new DomainException('Dokumen yang di-confirm harus berstatus REGISTERED.');
    }

    /**
     * @return bool
     * @throws DomainException
     */
    public function isAllowedToUnconfirm()
    {
        if (in_array($this->IDStatusDokumen, self::IDSTATUS_ALLOW_UNCONFIRM)) {
            return true;
        }
        throw new DomainException('Dokumen yang di-unconfirm harus berstatus REGISTERED atau CONFIRMED.');
    }

    /**
     * @return bool
     * @throws DomainException
     */
    public function isAllowedToApprove()
    {
        if (in_array($this->IDStatusDokumen, self::IDSTATUS_ALLOW_APPROVE)) {
            return true;
        }
        throw new DomainException('Dokumen yang di-approve harus berstatus CONFIRMED.');
    }

    /**
     * @return bool
     * @throws DomainException
     */
    public function isAllowedToUnapproved()
    {
        if (in_array($this->IDStatusDokumen, self::IDSTATUS_ALLOW_UNAPPROVE)) {
            return true;
        }
        throw new DomainException('Dokumen yang akan di-unapprove harus berstatus APPROVED atau CONFIRMED.');
    }
}
