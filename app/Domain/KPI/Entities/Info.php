<?php

namespace App\Domain\KPI\Entities;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    /**
     * @var string
     */
    protected $table = 'InfoKPI';

    /**
     * @var array
     */
    protected $fillable = [
        'ID', 'Judul', 'Tanggal_publish', 'Tanggal_berakhir', 'Gambar', 'Informasi', 'user_id'
    ];

    /**
     * @var string
     */
    public $primaryKey = 'ID';

    /**
     * @var bool
     */
    public $timestamps = false;
}
