<?php

namespace App\Domain\User\Entities; 

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed karyawan
 */
class Direksi extends Model
{
    //use Notifiable;

    /**
     * @var string
     */
    public $primaryKey = 'Npk';

    /**
     * @var string
     */
    protected $table = 'Ms_Direksi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
 
    /**
     * Belongs to karyawan relationship
     */
    public function organization()
    {
        return $this->hasOne('App\Domain\Karyawan\Entities\OrganizationalAssignment', 'Npk', 'NPK')->withDefault(); 
    } 
    
}
