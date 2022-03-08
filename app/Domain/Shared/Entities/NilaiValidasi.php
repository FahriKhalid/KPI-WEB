<?php

namespace App\Domain\Shared\Entities;

use Illuminate\Database\Eloquent\Model;

class NilaiValidasi extends Model
{
    /**
     * @var string
     */
    public $primaryKey = 'ID';

    /**
     * @var string
     */
    protected $table = 'VL_NilaiValidasi';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'Penilaian', 'Prosentase', 'Keterangan'
    ];
}
