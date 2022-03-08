<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 07/23/2017
 * Time: 10:31 PM
 */

namespace App\Domain\User\Entities;

use Illuminate\Database\Eloquent\Model;

class UserPrivilege extends Model
{
    /**
     * @var string
     */
    protected $table = 'UserPrivileges';

    /**
     * @var array
     */
    protected $fillable = [
        'IDUser', 'IDRole'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'IDUser', 'ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userrole()
    {
        return $this->belongsTo(UserRole::class, 'IDRole', 'ID');
    }
}
