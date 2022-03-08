<?php

namespace App\Domain\Karyawan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Domain\Karyawan\Services\PositionAbbreviation;
use App\Domain\Realisasi\Entities\HeaderRealisasiKPI;
use App\Domain\Rencana\Entities\HeaderRencanaKPI;
use App\Domain\User\Entities\User;

class Karyawan extends Model
{
    use Notifiable;
    /**
     * @var string
     */
    protected $table = 'Ms_Karyawan';

    /**
     * @var array
     */
    protected $fillable = [
        'NPK', 'Email', 'NamaKaryawan'
    ];

    /**
     * @var string
     */
    public $primaryKey = 'NPK';

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
    public function organization()
    {
        return $this->hasOne(OrganizationalAssignment::class, 'NPK')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'NPK')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rencanakpi()
    {
        return $this->hasMany(HeaderRencanaKPI::class, 'NPK', 'NPK');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function realisasikpi()
    {
        return $this->hasMany(HeaderRealisasiKPI::class, 'NPK', 'NPK');
    }

    /**
     * @return bool
     */
    public function isGeneralManager()
    {
        $positionAbbreviation = new PositionAbbreviation();

        if($this->organization->position->PositionAbbreviation != null){ 
            $positionAbbreviation->position($this->organization->position->PositionAbbreviation);
        }else{ 
            $posAbbreviationCode = \DB::table("Ms_Direksi")->where("Npk", \Auth::user()->NPK)->first();
            if($posAbbreviationCode){
                $positionAbbreviation->position($posAbbreviationCode->PositionAbbreviation);
            }
        }

        return $positionAbbreviation->isGeneralManager(); 
    }

    /**
     * @return bool
     */
    public function isManager()
    {
        $positionAbbreviation = new PositionAbbreviation();

        if($this->organization->position->PositionAbbreviation != null){
            $positionAbbreviation->position($this->organization->position->PositionAbbreviation);
        }else{
            $posAbbreviationCode = \DB::table("Ms_Direksi")->where("Npk", \Auth::user()->NPK)->first();
            if($posAbbreviationCode){
                $positionAbbreviation->position($posAbbreviationCode->PositionAbbreviation);
            }
        }


        
        return $positionAbbreviation->isManager();
    }

    /**
     * @return bool
     */
    public function isDireksi()
    {
        $positionAbbreviation = new PositionAbbreviation();

        if($this->organization->position->PositionAbbreviation != null){
            $positionAbbreviation->position($this->organization->position->PositionAbbreviation);
        }else{
            $posAbbreviationCode = \DB::table("Ms_Direksi")->where("Npk", \Auth::user()->NPK)->first();
            if($posAbbreviationCode){
                $positionAbbreviation->position($posAbbreviationCode->PositionAbbreviation);
            }
        }
        
        return $positionAbbreviation->isDirektorat();
    }

    /**
     * @return bool
     */
    public function isUnitKerja()
    {   
        return ($this->isManager() || $this->isGeneralManager());
    }

    /**
     * @param $grade
     * @return bool
     */
    public static function mustHaveMandatoryKPI($grade)
    {
        return ($grade == '1A' || $grade == '2A');
    }
}
