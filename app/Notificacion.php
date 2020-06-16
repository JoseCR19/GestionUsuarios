<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Notificacion extends Model {

    public $timestamps = false;
    protected $table = 'noti_usua';
    protected $primaryKey = 'intIdNoti';
    protected $fillable = [
        'asun_noti',
        'modu_prog',
        'varDescNoti',
        'dateNoti',
        'intIdEsta',
        'acti_usua',
        'acti_hora',
        'ruta_prog',
        'varNombarch',
        'varDetaArch'
    ];

}
