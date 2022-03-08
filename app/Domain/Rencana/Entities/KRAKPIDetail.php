<?php

namespace App\Domain\Rencana\Entities;

use Illuminate\Database\Eloquent\Model;

class KRAKPIDetail extends Model
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
    protected $table = 'Tr_KRAKPIRencanaDetil';

    /**
     * @var array
     */
    protected $fillable = [
        'IsApproved', 'IsCascaded'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    public $casts = [
        'IsApproved' => 'boolean',
        'IsCascaded' => 'boolean'
    ];

    /**
     * Reference KPI atasan
     * @return mixed
     */
    public function detailkpi()
    {
        return $this->belongsTo(DetilRencanaKPI::class, 'IDKPIAtasan', 'ID')->withDefault();
    }
 
    /**
     * Cascaded kpi item
     * @return mixed
     */
    public function cascadedkpi()
    {
        return $this->hasMany(DetilRencanaKPI::class, 'IDKRAKPIRencanaDetil', 'ID');
    }
}
