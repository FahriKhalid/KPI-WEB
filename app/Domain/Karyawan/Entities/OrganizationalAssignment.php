<?php

namespace App\Domain\Karyawan\Entities;

use Illuminate\Database\Eloquent\Model;

class OrganizationalAssignment extends Model
{
    /**
     * @var string
     */
    protected $table = 'View_OrganizationalAssignment';

    /**
     * @var array
     */
    protected $fillable = [
        'NPK', 'Grade', 'PositionID', 'Shift'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @return mixed
     */
    public function position()
    {
        return $this->belongsTo(MasterPosition::class, 'PositionID', 'PositionID')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'NPK', 'NPK');
    }
}
