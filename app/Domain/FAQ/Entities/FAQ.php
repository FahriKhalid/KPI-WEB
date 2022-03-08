<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 09/08/2017
 * Time: 12:31 PM
 */

namespace App\Domain\FAQ\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Karyawan\Entities\Karyawan;

class FAQ extends Model
{
    /**
     * @var string
     */
    protected $table='FAQ';
    /**
     * @var string
     */
    protected $primaryKey = 'ID';
    /**
     * @var bool
     */
    public $incrementing = false;
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = [
        'ID','Question','Answer','Aktif','AskedBy','AskedOn','AnsweredBy','AnsweredOn','Data'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function askedby()
    {
        return $this->belongsTo(Karyawan::class, 'AskedBy', 'NPK');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function answeredby()
    {
        return $this->belongsTo(Karyawan::class, 'AnsweredBy', 'NPK');
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return ($this->Aktif == true);
    }

    /**
     * @return bool
     */
    public function isAnswered()
    {
        return ($this->Answer !=null);
    }
    /**
     * @return bool
     */
    public function isNotAnswered()
    {
        return ($this->Answer ==null);
    }

    /**
     * @return bool
     */
    public function isAnsweredAndActive()
    {
        return ($this->Aktif == true and $this->Answer !=null);
    }
}
