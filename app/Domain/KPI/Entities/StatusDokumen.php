<?php

namespace App\Domain\KPI\Entities;

use Illuminate\Database\Eloquent\Model;

class StatusDokumen extends Model
{
    /**
     * @var string
     */
    public $primaryKey = 'ID';

    /**
     * @var string
     */
    protected $table = 'VL_StatusDokumen';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'KodeStatus', 'StatusDokumen', 'Keterangan'
    ];

    /**
     * @param $id
     * @return string
     */
    public static function parseDocument($id)
    {
        switch ($id) {
            case 1:
                return 'Draft';
                break;
            case 2:
                return 'Registered';
                break;
            case 3:
                return 'Confirmed';
                break;
            case 4:
                return 'Approved';
                break;
            case 5:
                return 'Cancelled';
                break;
            case 6:
                return 'Submitted';
                break;
            case 7:
                return 'Validated';
                break;
            case 8:
                return 'Applied';
                break;
        }
    }
}
