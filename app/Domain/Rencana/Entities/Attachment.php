<?php

namespace App\Domain\Rencana\Entities;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
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
    protected $table = 'Tr_AttachmentRencanaKPI';

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
}
