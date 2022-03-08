<?php

namespace App\Domain\User\Entities;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    /**
     * @var string
     */
    protected $table = 'UserRoles';

    /**
     * @var array
     */
    protected $fillable = [
        'Role', 'DeskripsiRole', 'Keterangan'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
