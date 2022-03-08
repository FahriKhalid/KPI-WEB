<?php

namespace App\Domain\KPI\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domain\User\Entities\User;

class Kompetensi extends Model
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
    protected $table = 'Ms_Kompetensi';

    /**
     * @var array
     */
    protected $fillable = [
        'ID', 'PositionID', 'Keterangan', 'CreatedBy', 'CreatedOn', 'UpdatedBy', 'UpdatedOn'
    ];

    /**
     * @return mixed
     */
    public function position()
    {
        return $this->belongsTo(MasterPosition::class, 'PositionID', 'ID')->withDefault();
    }

    /**
     * @var bool
     */
    public $timestamps = false;

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
}
