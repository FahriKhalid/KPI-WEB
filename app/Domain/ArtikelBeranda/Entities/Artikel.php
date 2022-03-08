<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 09/12/2017
 * Time: 12:40 PM
 */

namespace App\Domain\ArtikelBeranda\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Karyawan\Entities\Karyawan;

class Artikel extends Model
{
    /**
     * @var string
     */
    protected $table='Articles';
    /**
     * @var string
     */
    protected $primaryKey = 'ID';
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = [
        'ID','Title','Content','Aktif','CreatedBy','CreatedOn','UpdatedBy','UpdatedOn'
    ];
}
