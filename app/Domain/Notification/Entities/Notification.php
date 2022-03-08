<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 09/24/2017
 * Time: 12:40 PM
 */

namespace App\Domain\Notification;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * @var string
     */
    public $primaryKey = 'id';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $table = 'notifications';

    /**
     * @var array
     */
    protected $fillable = [
        'type','notifiable','data','read_at'
    ];

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @return mixed
     */
    public function data()
    {
        return json_decode($this->data);
    }
}
