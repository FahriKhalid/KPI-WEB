<?php

namespace App\Domain\Realisasi\Entities;

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
    protected $table = 'Tr_AttachmentRealisasiKPI';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return mixed
     */
    public function headerrealisasi()
    {
        return $this->belongsTo(HeaderRealisasiKPI::class, 'IDKPIRealisasiHeader', 'ID')->withDefault();
    }
}
