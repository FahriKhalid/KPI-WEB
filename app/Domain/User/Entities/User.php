<?php

namespace App\Domain\User\Entities;

//use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Session;
use App\Domain\Karyawan\Entities\Karyawan;
use App\Domain\Karyawan\Services\PositionAbbreviation;

/**
 * @property mixed karyawan
 */
class User extends Authenticatable
{
    //use Notifiable;
    /**
     * @var string
     */
    public $primaryKey = 'ID';

    /**
     * @var string
     */
    protected $table = 'Users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'IDRole', 'NPK', 'username', 'password', 'Keterangan'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Belongs to karyawan relationship
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'NPK', 'NPK')->withDefault([
            'NamaKaryawan' => '-'
        ]);
    }


    /**
     * Each user has one user role
     */
    public function UserRole()
    {
        return $this->hasOne(UserRole::class, 'ID', 'IDRole');
    }

    /**
     * Each user has many user privileges
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Roles()
    {
        return $this->belongsToMany(UserRole::class, 'UserPrivileges', 'IDUser', 'IDRole');
    }

    /**
     * @param $query
     * @param $npklama
     * @return mixed
     */
    public function scopeNpkLama($query, $npklama)
    {
        return $query->where('username', $npklama);
    }
    

    /**
     * Check active user abbreviation
     * @return PositionAbbreviation
     */
    public function abbreviation()
    {
        $positionAbbreviation = new PositionAbbreviation();
        $positionAbbreviation_ = $this->karyawan->organization->position->PositionAbbreviation;
        $codeShift = $this->karyawan->organization->Shift;

        if (empty($positionAbbreviation_) || empty($codeShift)) {
            $posAbbreviationCode = \DB::table("Ms_Direksi")->where("Npk", \Auth::user()->NPK)->first();
            if($posAbbreviationCode){
                $positionAbbreviation_ = $posAbbreviationCode->PositionAbbreviation;
                $codeShift = $posAbbreviationCode->Shift;
            } else{
                return null;
            }
        }
        
        $positionAbbreviation->position($positionAbbreviation_)->codeShift($codeShift);
        return $positionAbbreviation;
    }
}
