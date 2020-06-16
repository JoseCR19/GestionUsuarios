<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class susc_deta extends Model {

    public $timestamps = false;
    protected $table = 'susc_deta';
    protected $fillable = [
        'intIdFire',
        'textLlave_usua',
        'textTock_usua',
        'varCodiUsua'
    ];
}
