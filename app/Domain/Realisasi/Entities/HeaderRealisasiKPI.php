<?php

namespace App\Domain\Realisasi\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Karyawan\Entities\Karyawan;
use App\Domain\Karyawan\Entities\MasterPosition;
use App\Domain\KPI\Entities\JenisPeriode;
use App\Domain\KPI\Entities\PeriodeAktif;
use App\Domain\KPI\Entities\StatusDokumen;
use App\Domain\Rencana\Entities\HeaderRencanaKPI;
use App\Exceptions\DomainException;

class HeaderRealisasiKPI extends Model
{
    const IDSTATUS_ALLOW_REGISTER = [1];
    const IDSTATUS_ALLOW_UNREGISTER = [2];
    const IDSTATUS_ALLOW_CONFIRM = [2];
    const IDSTATUS_ALLOW_UNCONFIRM = [2, 3];
    const IDSTATUS_ALLOW_APPROVE = [3];
    const IDSTATUS_ALLOW_APPROVEUNITKERJA = [2, 3];
    const IDSTATUS_ALLOW_UNAPPROVE = [3, 4];
    const IDSTATUS_ALLOW_UNAPPROVEUNITKERJA = [2, 4];
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
    protected $table = 'Tr_KPIRealisasiHeader';

    /**
     * @var array
     */
    protected $fillable = [
        'ID','IDKPIRencanaHeader','Grade','IDMasterPosition','NPKAtasanLangsung',
        'JabatanAtasanLangsung','NPKAtasanBerikutnya','JabatanAtasanBerikutnya','IDStatusDokumen',
        'NilaiAkhir','NilaiValidasi','Keterangan','CreatedBy','CreatedOn','ApproveBy','ApproveOn',
        'CatatanUnapprove','ConfirmedBy','ConfirmedOn','CatatanUnconfirm','RegisteredBy','RegisteredOn',
        'CanceledBy','CanceledOn','AlasanCancel','Field1','Field2','Field3'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @return mixed
     */
    public function headerrencanakpi()
    {
        return $this->belongsTo(HeaderRencanaKPI::class, 'IDKPIRencanaHeader')->withDefault();
    }

    /**
     * @return mixed
     */
    public function detail()
    {
        return $this->hasMany(DetilRealisasiKPI::class, 'IDKPIRealisasiHeader');
    }
    /**
     * @return mixed
     */
    public function masterposition()
    {
        return $this->belongsTo(MasterPosition::class, 'IDMasterPosition', 'PositionID')->withDefault();
    }
    /**
     * @return mixed
     */
    public function statusdokumen()
    {
        return $this->belongsTo(StatusDokumen::class, 'IDStatusDokumen', 'ID')->withDefault();
    }

    /**
     * @return mixed
     */
    public function periodeaktif()
    {
        return $this->belongsTo(PeriodeAktif::class, 'KodePeriode', 'ID')->withDefault();
    }

    /**
     * @return $this
     */
    public function jenisperiode()
    {
        return $this->belongsTo(JenisPeriode::class, 'IDJenisPeriode', 'ID')->withDefault();
    }

    /**
     * @return mixed
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'NPK', 'NPK')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function validasiunitkerja()
    {
        return $this->hasMany(ValidasiUnitKerja::class, 'IDKPIRealisasiHeader', 'ID');
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
     */
    public function isApplied()
    {
        return ($this->IDStatusDokumen == 8);
    }

    /**
     * @return bool
     */
    public function isSuggested()
    {
        return ($this->IDStatusDokumen == 9);
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'IDKPIRealisasiHeader');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeUnitKerja($query)
    {
        return $query->where('IsUnitKerja', 1);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeIndividu($query)
    {
        return $query->where('IsUnitKerja', 0);
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
    public function isAllowedToApprove($jeniskpi = 'individu')
    {
        if ($jeniskpi == 'unitkerja') {
            if (in_array($this->IDStatusDokumen, self::IDSTATUS_ALLOW_APPROVEUNITKERJA)) {
                return true;
            }
            throw new DomainException('Dokumen yang di-approve harus berstatus REGISTERED.');
        }
        if ($jeniskpi == 'individu') {
            if (in_array($this->IDStatusDokumen, self::IDSTATUS_ALLOW_APPROVE)) {
                return true;
            }
            throw new DomainException('Dokumen yang di-approve harus berstatus CONFIRMED.');
        }
    }

    /**
     * @return bool
     * @throws DomainException
     */
    public function isAllowedToUnapproved($jeniskpi = 'individu')
    {
        if ($jeniskpi == 'individu') {
            if (in_array($this->IDStatusDokumen, self::IDSTATUS_ALLOW_UNAPPROVE)) {
                return true;
            }
            throw new DomainException('Dokumen yang akan di-unapprove harus berstatus APPROVED atau CONFIRMED.');
        }

        if ($jeniskpi == 'unitkerja') {
            if (in_array($this->IDStatusDokumen, self::IDSTATUS_ALLOW_UNAPPROVEUNITKERJA)) {
                return true;
            }
            throw new DomainException('Dokumen unit kerja yang akan di-unapprove harus berstatus REGISTERED atau APPROVED.');
        }
    }
}
